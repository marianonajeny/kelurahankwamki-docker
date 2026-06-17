// Lightbox for gallery
document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-lightbox]');
    if (!trigger) return;

    e.preventDefault();
    const src = trigger.dataset.lightbox || trigger.href;
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4';
    overlay.setAttribute('role', 'dialog');
    overlay.innerHTML = `
        <button type="button" class="absolute right-4 top-4 text-3xl text-white hover:text-kwamki-gold" aria-label="Tutup">&times;</button>
        <img src="${src}" alt="" class="max-h-[90vh] max-w-full rounded-lg object-contain">
    `;
    overlay.addEventListener('click', (ev) => {
        if (ev.target === overlay || ev.target.tagName === 'BUTTON') {
            overlay.remove();
        }
    });
    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
    overlay.querySelector('button').addEventListener('click', () => {
        overlay.remove();
        document.body.style.overflow = '';
    });
});

// Permohonan notifications (admin / lurah dashboard)
const NOTIF_STORAGE_KEY = 'permohonanNotifSince';
const NOTIF_POLL_MS = 30_000;

function initPermohonanNotifications() {
    const body = document.body;
    const url = body.dataset.permohonanNotifUrl;
    if (!url) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const role = body.dataset.permohonanNotifRole ?? 'admin';
    // Baseline saat halaman dibuka agar surat yang sudah di antrian tidak memicu toast.
    let since = new Date().toISOString();
    localStorage.setItem(NOTIF_STORAGE_KEY, since);

    const poll = async () => {
        try {
            const res = await fetch(`${url}?since=${encodeURIComponent(since)}`, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrf,
                },
                credentials: 'same-origin',
            });
            if (!res.ok) return;

            const data = await res.json();
            updatePermohonanBadge(data.badge ?? 0);

            if (role === 'lurah') {
                updateLurahBanner(data.menunggu_verifikasi ?? 0);
            }

            const baru = Array.isArray(data.baru) ? data.baru : [];
            baru.forEach((item) => showPermohonanToast(item, role));

            if (data.checked_at) {
                since = data.checked_at;
                localStorage.setItem(NOTIF_STORAGE_KEY, since);
            }
        } catch {
            // Abaikan error jaringan agar polling tidak mengganggu
        }
    };

    setTimeout(poll, 3_000);
    setInterval(poll, NOTIF_POLL_MS);
}

function updatePermohonanBadge(count) {
    const badge = document.getElementById('permohonan-badge');
    if (!badge) return;

    const n = Math.max(0, Number(count) || 0);
    if (n > 0) {
        badge.textContent = String(n);
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

function updateLurahBanner(count) {
    const banner = document.getElementById('antrian-lurah-banner');
    const text = document.getElementById('antrian-lurah-banner-text');
    const n = Math.max(0, Number(count) || 0);

    if (n > 0) {
        if (text) {
            text.textContent = `${n} surat menunggu verifikasi di antrian Anda.`;
        }
        banner?.classList.remove('hidden');
    } else {
        banner?.classList.add('hidden');
    }
}

function showPermohonanToast(item, role) {
    const container = document.getElementById('permohonan-toast-container');
    if (!container || !item?.url) return;

    const title = role === 'lurah' ? 'Surat baru di antrian' : 'Permohonan baru';
    const layanan = item.layanan ? ` — ${item.layanan}` : '';
    const subtitle = `${item.nomor ?? ''}${layanan}`.trim();

    const toast = document.createElement('div');
    toast.className =
        'pointer-events-auto rounded-lg border border-kwamki-forest/20 bg-white px-4 py-3 text-sm shadow-lg ring-1 ring-black/5';
    toast.innerHTML = `
        <p class="font-semibold text-kwamki-forest-dark">${escapeHtml(title)}</p>
        <p class="mt-0.5 text-gray-600">${escapeHtml(subtitle)}</p>
        <p class="text-gray-500">${escapeHtml(item.nama ?? '')}</p>
        <a href="${escapeHtml(item.url)}" class="mt-2 inline-block font-semibold text-kwamki-ocean hover:underline">Buka detail</a>
    `;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition', 'duration-300');
        setTimeout(() => toast.remove(), 300);
    }, 8_000);
}

function escapeHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function initStaggerDelays() {
    document.querySelectorAll('.reveal-stagger').forEach((container) => {
        container.querySelectorAll(':scope > .reveal').forEach((el, i) => {
            el.style.transitionDelay = `${Math.min(i, 11) * 80}ms`;
        });
    });
}

function initScrollReveal() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const targets = document.querySelectorAll('.reveal');
    if (!targets.length) {
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
    );

    targets.forEach((el) => observer.observe(el));
}

document.addEventListener('DOMContentLoaded', () => {
    initStaggerDelays();
    initScrollReveal();
    initPermohonanNotifications();
});
