<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem acessar as configurações.');
        }

        $siteSettings = Setting::getByGroup('site');
        $smtpSettings = Setting::getByGroup('smtp');
        $socialSettings = Setting::getByGroup('social');

        // Mesclar configurações sociais com site para compatibilidade
        $siteSettings = array_merge($siteSettings, $socialSettings);

        return view('admin.settings.index', compact('siteSettings', 'smtpSettings'));
    }

    public function update(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem acessar as configurações.');
        }

        $validated = $request->validate([
            // Site Settings
            'site_name' => ['required', 'string', 'max:255'],
            'site_description' => ['required', 'string', 'max:500'],
            'site_email' => ['required', 'email', 'max:255'],
            'site_logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            
            // Social Media Settings
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_youtube' => ['nullable', 'url', 'max:255'],
            'social_tiktok' => ['nullable', 'url', 'max:255'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            
            // SMTP Settings
            'mail_mailer' => ['required', 'string', 'in:smtp,sendmail,log'],
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'string', 'in:tls,ssl'],
            'mail_from_address' => ['required', 'email', 'max:255'],
            'mail_from_name' => ['required', 'string', 'max:255'],
        ]);

        // Processar upload da logo
        $logoPath = Setting::get('site_logo');
        if ($request->hasFile('site_logo')) {
            // Deletar logo antiga se existir
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            
            // Salvar nova logo
            $logoPath = $request->file('site_logo')->store('logos', 'public');
        }

        // Atualizar configurações do site
        Setting::set('site_name', $validated['site_name'], 'text', 'site', 'Nome do site');
        Setting::set('site_description', $validated['site_description'], 'text', 'site', 'Descrição do site');
        Setting::set('site_email', $validated['site_email'], 'email', 'site', 'Email de contato do site');
        if ($logoPath) {
            Setting::set('site_logo', $logoPath, 'text', 'site', 'Logo do site');
        }

        // Atualizar configurações de Redes Sociais
        Setting::set('social_instagram', $validated['social_instagram'] ?? '', 'url', 'social', 'Link do Instagram');
        Setting::set('social_facebook', $validated['social_facebook'] ?? '', 'url', 'social', 'Link do Facebook');
        Setting::set('social_linkedin', $validated['social_linkedin'] ?? '', 'url', 'social', 'Link do LinkedIn');
        Setting::set('social_twitter', $validated['social_twitter'] ?? '', 'url', 'social', 'Link do Twitter/X');
        Setting::set('social_youtube', $validated['social_youtube'] ?? '', 'url', 'social', 'Link do YouTube');
        Setting::set('social_tiktok', $validated['social_tiktok'] ?? '', 'url', 'social', 'Link do TikTok');
        Setting::set('contact_whatsapp', $validated['contact_whatsapp'] ?? '', 'text', 'social', 'Número do WhatsApp');

        // Atualizar configurações SMTP
        Setting::set('mail_mailer', $validated['mail_mailer'], 'text', 'smtp', 'Driver de email');
        Setting::set('mail_host', $validated['mail_host'] ?? '', 'text', 'smtp', 'Servidor SMTP');
        Setting::set('mail_port', $validated['mail_port'] ?? '587', 'number', 'smtp', 'Porta SMTP');
        Setting::set('mail_username', $validated['mail_username'] ?? '', 'text', 'smtp', 'Usuário SMTP');
        
        // Só atualizar senha se foi fornecida
        if (!empty($validated['mail_password'])) {
            Setting::set('mail_password', $validated['mail_password'], 'text', 'smtp', 'Senha SMTP');
        }
        
        Setting::set('mail_encryption', $validated['mail_encryption'] ?? 'tls', 'text', 'smtp', 'Criptografia');
        Setting::set('mail_from_address', $validated['mail_from_address'], 'email', 'smtp', 'Email remetente');
        Setting::set('mail_from_name', $validated['mail_from_name'], 'text', 'smtp', 'Nome do remetente');

        // Atualizar arquivo .env (opcional - apenas se necessário)
        // Por enquanto, apenas salvamos no banco de dados

        return redirect()->route('admin.settings.index')->with('success', 'Configurações atualizadas com sucesso!');
    }

    public function testEmail(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        try {
            // Buscar configurações SMTP do banco
            $smtpSettings = Setting::getByGroup('smtp');
            
            // Atualizar configuração do Laravel temporariamente
            config([
                'mail.mailers.smtp.host' => $smtpSettings['mail_host'] ?? config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $smtpSettings['mail_port'] ?? config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.username' => $smtpSettings['mail_username'] ?? config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $smtpSettings['mail_password'] ?? config('mail.mailers.smtp.password'),
                'mail.mailers.smtp.encryption' => $smtpSettings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'),
                'mail.from.address' => $smtpSettings['mail_from_address'] ?? config('mail.from.address'),
                'mail.from.name' => $smtpSettings['mail_from_name'] ?? config('mail.from.name'),
            ]);

            // Enviar email de teste
            \Illuminate\Support\Facades\Mail::raw('Este é um email de teste do sistema Jornal a Borda. Se você recebeu esta mensagem, as configurações SMTP estão funcionando corretamente!', function ($message) use ($validated, $smtpSettings) {
                $message->to($validated['test_email'])
                        ->subject('Teste de Email - Jornal a Borda');
            });

            return back()->with('success', 'Email de teste enviado com sucesso para ' . $validated['test_email'] . '!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar email: ' . $e->getMessage());
        }
    }
}
