/* OLDWEAR.SCND — interaksi ringan tanpa dependency */
(function () {
    'use strict';

    // Navbar shadow saat scroll
    const nav = document.querySelector('.navbar');
    if (nav) {
        const onScroll = () => nav.classList.toggle('is-scrolled', window.scrollY > 8);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // Toggle (menu mobile, panel filter)
    document.querySelectorAll('[data-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = document.querySelector(btn.dataset.toggle);
            if (!target) return;
            const open = target.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    });

    // Admin Mobile Sidebar Drawer
    const adminSidebar = document.getElementById('admin-sidebar');
    const adminToggle = document.getElementById('admin-menu-toggle');
    const adminBackdrop = document.getElementById('admin-backdrop');
    const adminClose = document.getElementById('admin-sidebar-close');

    if (adminSidebar && adminToggle) {
        const openAdminSidebar = () => {
            adminSidebar.classList.add('is-open');
            if (adminBackdrop) adminBackdrop.classList.add('is-open');
            adminToggle.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        };

        const closeAdminSidebar = () => {
            adminSidebar.classList.remove('is-open');
            if (adminBackdrop) adminBackdrop.classList.remove('is-open');
            adminToggle.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        };

        adminToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            if (adminSidebar.classList.contains('is-open')) {
                closeAdminSidebar();
            } else {
                openAdminSidebar();
            }
        });

        if (adminBackdrop) {
            adminBackdrop.addEventListener('click', closeAdminSidebar);
        }

        if (adminClose) {
            adminClose.addEventListener('click', closeAdminSidebar);
        }

        // Tutup saat item menu diklik di mobile
        adminSidebar.querySelectorAll('.admin-nav-item').forEach((item) => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 960) {
                    closeAdminSidebar();
                }
            });
        });

        // Tutup dengan tombol Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && adminSidebar.classList.contains('is-open')) {
                closeAdminSidebar();
            }
        });

        // Reset saat layar diperbesar ke desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 960 && adminSidebar.classList.contains('is-open')) {
                closeAdminSidebar();
            }
        });
    }

    // Galeri produk
    const mainImg = document.querySelector('[data-gallery-main]');
    document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
        thumb.addEventListener('click', () => {
            if (!mainImg) return;
            mainImg.style.opacity = '0';
            setTimeout(() => {
                mainImg.src = thumb.dataset.galleryThumb;
                mainImg.style.opacity = '1';
            }, 150);
            document.querySelectorAll('[data-gallery-thumb]').forEach((t) => t.classList.remove('is-active'));
            thumb.classList.add('is-active');
        });
    });

    // Konfirmasi sebelum submit (hapus data)
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            if (!window.confirm(form.dataset.confirm)) e.preventDefault();
        });
    });

    // Preview gambar sebelum upload
    document.querySelectorAll('input[type=file][data-preview]').forEach((input) => {
        input.addEventListener('change', () => {
            const img = document.querySelector(input.dataset.preview);
            const file = input.files && input.files[0];
            if (img && file) {
                img.src = URL.createObjectURL(file);
                img.hidden = false;
            }
        });
    });

    // Auto-kompresi gambar kamera HP & pencegahan multi-submit "muter-muter"
    const compressImageFile = (file, maxDim = 1600, quality = 0.85) => {
        return new Promise((resolve) => {
            if (!file || !file.type || !file.type.startsWith('image/') || file.type === 'image/svg+xml' || file.size < 500 * 1024) {
                return resolve(file);
            }
            const img = new Image();
            const url = URL.createObjectURL(file);
            img.onload = () => {
                URL.revokeObjectURL(url);
                let w = img.width;
                let h = img.height;
                if (w > maxDim || h > maxDim) {
                    if (w > h) {
                        h = Math.round((h * maxDim) / w);
                        w = maxDim;
                    } else {
                        w = Math.round((w * maxDim) / h);
                        h = maxDim;
                    }
                }
                const canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, w, h);
                canvas.toBlob((blob) => {
                    if (blob && blob.size < file.size) {
                        const newName = file.name.replace(/\.[^.]+$/, '') + '.jpg';
                        resolve(new File([blob], newName, { type: 'image/jpeg', lastModified: Date.now() }));
                    } else {
                        resolve(file);
                    }
                }, 'image/jpeg', quality);
            };
            img.onerror = () => {
                URL.revokeObjectURL(url);
                resolve(file);
            };
            img.src = url;
        });
    };

    document.querySelectorAll('form[enctype*="multipart"]').forEach((form) => {
        form.addEventListener('submit', async (e) => {
            if (form.dataset.submitting === 'true') {
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const fileInputs = form.querySelectorAll('input[type="file"]');
            let hasLargeFiles = false;

            fileInputs.forEach((input) => {
                if (input.files) {
                    for (let i = 0; i < input.files.length; i++) {
                        if (input.files[i].size > 500 * 1024) {
                            hasLargeFiles = true;
                        }
                    }
                }
            });

            // Jika ada foto kamera berukuran besar (>500KB), kompres dulu sebelum kirim
            if (hasLargeFiles && typeof DataTransfer !== 'undefined') {
                e.preventDefault();
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-sm"></span> Mengompres foto...';
                }

                try {
                    for (const input of fileInputs) {
                        if (!input.files || input.files.length === 0) continue;
                        const dt = new DataTransfer();
                        for (let i = 0; i < input.files.length; i++) {
                            const compressed = await compressImageFile(input.files[i]);
                            dt.items.add(compressed);
                        }
                        input.files = dt.files;
                    }
                } catch (err) {
                    console.warn('Auto-compress failed, continuing standard upload:', err);
                }

                if (submitBtn) {
                    submitBtn.innerHTML = '<span class="spinner-sm"></span> Mengunggah produk...';
                }
                form.dataset.submitting = 'true';
                form.submit();
            } else {
                // Submit standar dengan spinner indikator
                form.dataset.submitting = 'true';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-sm"></span> Menyimpan...';
                }
            }
        });
    });

    // Toast auto-hide
    document.querySelectorAll('.toast-wrap .alert').forEach((el) => {
        setTimeout(() => {
            el.classList.add('is-hiding');
            setTimeout(() => el.remove(), 350);
        }, 4500);
    });

    // Reveal on scroll
    const reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && reveals.length) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach((el) => io.observe(el));
    } else {
        reveals.forEach((el) => el.classList.add('is-visible'));
    }

    // Modal Lightbox Testimoni
    window.openTestimonialModal = function(src, name) {
        const modal = document.getElementById('testimonialModal');
        const img = document.getElementById('testimonialModalImg');
        const caption = document.getElementById('testimonialModalCaption');
        if (!modal || !img) return;
        img.src = src;
        if (caption) caption.textContent = name ? 'Bukti Testimoni: ' + name : 'Bukti Testimoni';
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    window.closeTestimonialModal = function() {
        const modal = document.getElementById('testimonialModal');
        if (!modal) return;
        modal.style.display = 'none';
        document.body.style.overflow = '';
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closeTestimonialModal();
        }
    });

    // =========================================================
    // Page Preloader / Layar Memuat dengan Logo
    // =========================================================
    const preloader = document.getElementById('page-preloader');
    let preloaderFailsafeTimer = null;

    function showPreloader() {
        if (!preloader) return;
        preloader.classList.remove('is-hidden');
        preloader.setAttribute('aria-busy', 'true');
        clearTimeout(preloaderFailsafeTimer);
        // Failsafe auto-hide setelah 6 detik jika user batal navigasi atau download
        preloaderFailsafeTimer = setTimeout(hidePreloader, 6000);
    }

    function hidePreloader() {
        if (!preloader) return;
        preloader.classList.add('is-hidden');
        preloader.setAttribute('aria-busy', 'false');
        clearTimeout(preloaderFailsafeTimer);
    }

    // Expose fungsi ke global
    window.showPagePreloader = showPreloader;
    window.hidePagePreloader = hidePreloader;

    if (preloader) {
        // Hilangkan preloader saat load selesai dengan transisi halus
        const dismissInitial = () => {
            setTimeout(hidePreloader, 350);
        };

        if (document.readyState === 'complete') {
            dismissInitial();
        } else {
            window.addEventListener('load', dismissInitial);
        }

        // Failsafe awal: jangan biarkan loading macet lebih dari 2.5 detik
        setTimeout(hidePreloader, 2500);

        // BFCache: jika user klik tombol Back/Forward browser
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                hidePreloader();
            }
        });

        // Tampilkan preloader saat user klik link navigasi internal
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (!link) return;

            // Abaikan klik jika sudah dicegah atau dibuka di tab baru
            if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
            if (link.target === '_blank' || link.hasAttribute('download') || link.dataset.noLoader !== undefined) return;

            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
            if (href.includes('wa.me') || href.includes('api.whatsapp.com')) return;

            // Periksa origin host
            try {
                const targetUrl = new URL(link.href, window.location.href);
                if (targetUrl.origin !== window.location.origin) return;

                // Abaikan jika hanya hash pada halaman yang sama
                if (targetUrl.pathname === window.location.pathname && targetUrl.search === window.location.search) {
                    if (targetUrl.hash) return;
                }
            } catch (err) {
                return;
            }

            showPreloader();
        });

        // Tampilkan saat submit form normal
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.target === '_blank' || form.dataset.noLoader !== undefined) return;
            if (e.defaultPrevented) return;
            showPreloader();
        });
    }
})();
