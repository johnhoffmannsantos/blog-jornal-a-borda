@php
    $popularPosts = \App\Models\Post::where('status', 'published')
        ->orderBy('views', 'desc')
        ->take(5)
        ->get();
    
    $popularTags = \App\Models\Tag::withCount('posts')
        ->orderBy('posts_count', 'desc')
        ->take(12)
        ->get();
    
    $contactEmail = \App\Models\Setting::get('site_email', 'contato@jornalaborda.com.br');
    $contactWhatsApp = \App\Models\Setting::get('contact_whatsapp', '');
@endphp

<aside class="sidebar">
    <!-- Newsletter Widget -->
    <div class="widget widget-newsletter mb-4">
        <h3 class="widget-title">
            <i class="bi bi-envelope-paper me-2"></i>Receba nossas notícias
        </h3>
        <p class="widget-description">Histórias direto da quebrada, toda semana no seu email</p>
        <form action="#" method="post" class="newsletter-form" id="newsletterForm">
            @csrf
            <div class="mb-3">
                <input type="email" 
                       class="form-control" 
                       name="email" 
                       placeholder="Seu melhor email *" 
                       required
                       aria-label="Seu melhor email">
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-send me-2"></i>Receber
            </button>
            <div class="newsletter-message mt-2" style="display: none;">
                <p class="text-success small mb-0">
                    <i class="bi bi-check-circle me-1"></i>Verifique sua caixa de entrada ou a pasta de spam para confirmar sua assinatura.
                </p>
            </div>
        </form>
    </div>

    <!-- Ad Widget -->
    <div class="widget widget-ad mb-4">
        <div class="ad-cta">
            <h4>VISIBILIDADE COM<br>PROPÓSITO</h4>
            <p>Conecte-se com mais de 50 mil leitores mensais.</p>
            <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#advertiseModal">
                QUERO ANUNCIAR
            </button>
        </div>
    </div>

    <!-- Popular Posts Widget -->
    @if($popularPosts->count() > 0)
    <div class="widget widget-popular mb-4">
        <h3 class="widget-title">
            <i class="bi bi-fire me-2"></i>Mais Lidas
        </h3>
        <div class="popular-posts">
            @foreach($popularPosts as $index => $popularPost)
            <div class="popular-item">
                <span class="popular-number">{{ $index + 1 }}</span>
                <div class="popular-content">
                    <span class="popular-category">{{ $popularPost->category->name }}</span>
                    <h4 class="popular-title">
                        <a href="{{ route('post.show', $popularPost->slug) }}">
                            {{ Str::limit($popularPost->title, 60) }}
                        </a>
                    </h4>
                    <span class="popular-meta">
                        {{ $popularPost->published_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tags Widget -->
    @if($popularTags->count() > 0)
    <div class="widget widget-tags mb-4">
        <h3 class="widget-title">
            <i class="bi bi-hash me-2"></i>Em Alta Agora
        </h3>
        <div class="tags-list">
            @foreach($popularTags as $tag)
            <a href="{{ route('tag.show', $tag->slug) }}" class="tag">
                #{{ $tag->name }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Social Widget -->
    @php
        $socialLinks = [
            'instagram' => ['url' => \App\Models\Setting::get('social_instagram', ''), 'icon' => 'bi-instagram', 'name' => 'Instagram', 'class' => 'social-link--instagram'],
            'facebook' => ['url' => \App\Models\Setting::get('social_facebook', ''), 'icon' => 'bi-facebook', 'name' => 'Facebook', 'class' => 'social-link--facebook'],
            'linkedin' => ['url' => \App\Models\Setting::get('social_linkedin', ''), 'icon' => 'bi-linkedin', 'name' => 'LinkedIn', 'class' => 'social-link--linkedin'],
            'twitter' => ['url' => \App\Models\Setting::get('social_twitter', ''), 'icon' => 'bi-twitter-x', 'name' => 'Twitter/X', 'class' => 'social-link--twitter'],
            'youtube' => ['url' => \App\Models\Setting::get('social_youtube', ''), 'icon' => 'bi-youtube', 'name' => 'YouTube', 'class' => 'social-link--youtube'],
            'tiktok' => ['url' => \App\Models\Setting::get('social_tiktok', ''), 'icon' => 'bi-tiktok', 'name' => 'TikTok', 'class' => 'social-link--tiktok'],
            'whatsapp' => ['url' => \App\Models\Setting::get('contact_whatsapp', ''), 'icon' => 'bi-whatsapp', 'name' => 'WhatsApp', 'class' => 'social-link--whatsapp', 'is_whatsapp' => true],
        ];
        $activeSocialLinks = array_filter($socialLinks, function($link) {
            return !empty($link['url']);
        });
    @endphp
    @if(count($activeSocialLinks) > 0)
    <div class="widget widget-social mb-4">
        <h3 class="widget-title">
            <i class="bi bi-share me-2"></i>Siga-nos
        </h3>
        <div class="social-links">
            @foreach($activeSocialLinks as $key => $link)
                @if(isset($link['is_whatsapp']) && $link['is_whatsapp'])
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $link['url']) }}" target="_blank" rel="noopener" class="social-link {{ $link['class'] }}">
                    <i class="bi {{ $link['icon'] }}"></i>
                    <span>{{ $link['name'] }}</span>
                </a>
                @else
                <a href="{{ $link['url'] }}" target="_blank" rel="noopener" class="social-link {{ $link['class'] }}">
                    <i class="bi {{ $link['icon'] }}"></i>
                    <span>{{ $link['name'] }}</span>
                </a>
                @endif
            @endforeach
        </div>
    </div>
    @endif
</aside>

<style>
.sidebar {
    position: sticky;
    top: 20px;
}

.widget {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: var(--shadow-sm);
    margin-bottom: 24px;
}

.widget-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 16px;
    color: var(--text-dark);
    display: flex;
    align-items: center;
}

.widget-description {
    color: var(--text-light);
    font-size: 0.9rem;
    margin-bottom: 16px;
}

/* Newsletter Widget */
.newsletter-form .form-control {
    border-radius: 8px;
    padding: 12px;
    border: 1px solid var(--border-color);
}

.newsletter-form .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
}

/* Ad Widget */
.widget-ad {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    text-align: center;
}

.widget-ad .widget-title {
    color: white;
}

.ad-cta h4 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: white;
}

.ad-cta p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 20px;
}

.ad-cta .btn {
    background: white;
    color: var(--primary-color);
    font-weight: 600;
    border: none;
}

.ad-cta .btn:hover {
    background: var(--accent-color);
    transform: translateY(-2px);
}

/* Popular Posts Widget */
.popular-item {
    display: flex;
    gap: 12px;
    padding: 16px 0;
    border-bottom: 1px solid var(--border-color);
}

.popular-item:last-child {
    border-bottom: none;
}

.popular-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.popular-content {
    flex: 1;
}

.popular-category {
    display: block;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 4px;
}

.popular-title {
    margin: 0 0 6px 0;
    font-size: 0.95rem;
    line-height: 1.4;
}

.popular-title a {
    color: var(--text-dark);
    text-decoration: none;
    transition: var(--transition);
}

.popular-title a:hover {
    color: var(--primary-color);
}

.popular-meta {
    font-size: 0.8rem;
    color: var(--text-light);
}

/* Tags Widget */
.tags-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag {
    display: inline-block;
    padding: 6px 12px;
    background: var(--accent-color);
    color: var(--text-dark);
    border-radius: 20px;
    font-size: 0.85rem;
    text-decoration: none;
    transition: var(--transition);
    font-weight: 500;
}

.tag:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

/* Social Widget */
.social-links {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.social-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--accent-color);
    border-radius: 8px;
    text-decoration: none;
    color: var(--text-dark);
    transition: var(--transition);
    font-weight: 500;
}

.social-link:hover {
    background: var(--primary-color);
    color: white;
    transform: translateX(4px);
}

.social-link i {
    font-size: 1.2rem;
}

.social-link--instagram:hover {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
}

.social-link--facebook:hover {
    background: #1877F2;
}

.social-link--linkedin:hover {
    background: #0077B5;
}

.social-link--twitter:hover {
    background: #000000;
}

.social-link--youtube:hover {
    background: #FF0000;
}

.social-link--tiktok:hover {
    background: #000000;
}

.social-link--whatsapp:hover {
    background: #25D366;
}
</style>

<script>
document.getElementById('newsletterForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const messageDiv = form.querySelector('.newsletter-message');
    const email = form.querySelector('input[name="email"]').value;
    
    // Simular envio (aqui você pode integrar com um serviço de email marketing)
    messageDiv.style.display = 'block';
    form.querySelector('input[name="email"]').value = '';
    
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 5000);
});
</script>

