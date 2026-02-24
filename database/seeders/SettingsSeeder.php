<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Configurações Gerais
        Setting::set('site_name', 'Jornal a Borda', 'text', 'site', 'Nome do site');
        Setting::set('site_description', 'A Voz das Periferias de Osasco', 'text', 'site', 'Descrição do site');
        Setting::set('site_email', 'contato@jornalaborda.com.br', 'email', 'site', 'Email de contato do site');

        // Configurações SMTP
        Setting::set('mail_mailer', 'smtp', 'text', 'smtp', 'Driver de email (smtp, sendmail, etc)');
        Setting::set('mail_host', '', 'text', 'smtp', 'Servidor SMTP');
        Setting::set('mail_port', '587', 'number', 'smtp', 'Porta SMTP');
        Setting::set('mail_username', '', 'text', 'smtp', 'Usuário SMTP');
        Setting::set('mail_password', '', 'text', 'smtp', 'Senha SMTP');
        Setting::set('mail_encryption', 'tls', 'text', 'smtp', 'Criptografia (tls, ssl)');
        Setting::set('mail_from_address', 'noreply@jornalaborda.com.br', 'email', 'smtp', 'Email remetente');
        Setting::set('mail_from_name', 'Jornal a Borda', 'text', 'smtp', 'Nome do remetente');
    }
}
