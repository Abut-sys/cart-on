@if (auth()->check() && session('login_time'))
    <!-- Enhanced Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="ratingForm" method="POST" action="{{ route('rating.store') }}">
                @csrf
                <input type="hidden" name="information_id" value="{{ $information->id ?? 1 }}">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <!-- Close Button -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                            data-bs-dismiss="modal" aria-label="Close" style="z-index: 1050;">
                        <i class="fas fa-times"></i>
                    </button>

                    <div class="modal-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Perhatian!</strong>
                                </div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Enhanced Star Rating Section -->
                        <div class="text-center mb-4">
                            <h6 class="fw-bold text-dark mb-3">Seberapa puas Anda dengan layanan kami?</h6>
                            <div class="star-rating-container">
                                <div class="star-rating d-flex justify-content-center align-items-center gap-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="star-wrapper position-relative">
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="star-input">
                                            <label for="star{{ $i }}" class="star-label position-relative d-block" title="{{ $i }} bintang">
                                                <svg class="star-svg" width="40" height="40" viewBox="0 0 24 24" fill="none">
                                                    <path class="star-path" d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" stroke="#e0e0e0" stroke-width="1.5" fill="#f5f5f5"/>
                                                    <path class="star-fill" d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="#ffc107"/>
                                                </svg>
                                                <div class="star-pulse"></div>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                <div class="rating-text mt-3">
                                    <span id="ratingText" class="text-muted fst-italic">Pilih rating Anda</span>
                                </div>
                            </div>
                        </div>

                        <!-- Comment Section -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="fas fa-comment-dots me-2 text-primary"></i>
                                Komentar <span class="text-muted fw-normal">(opsional)</span>
                            </label>
                            <div class="position-relative">
                                <textarea name="comment" class="form-control border-2 rounded-3 shadow-sm"
                                    rows="4"
                                    placeholder="Ceritakan pengalaman Anda... Saran dan kritik sangat membantu kami untuk berkembang!"
                                    style="resize: none; padding-top: 12px; padding-bottom: 12px;"></textarea>
                                <div class="position-absolute bottom-0 end-0 p-2">
                                    <small class="text-muted">
                                        <span id="charCount">0</span>/500
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-2">
                        <div class="d-grid w-100">
                            <button type="submit" class="btn btn-lg rounded-3 shadow-sm"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white;"
                                disabled id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Kirim Penilaian
                            </button>
                        </div>
                        <div class="text-center mt-2">
                            <small class="text-muted">Terima kasih atas waktu Anda! 🙏</small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- CSS -->
    <style>
        /* Modal Background Transparency */
        .modal {
            background-color: rgba(0, 0, 0, 0.3) !important;
        }

        .modal-content {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Custom Close Button */
        .btn-close {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            opacity: 1;
        }

        .btn-close:hover {
            background: rgba(220, 53, 69, 0.1);
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }

        .btn-close i {
            color: #6c757d;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .btn-close:hover i {
            color: #dc3545;
        }

        /* Star Rating Styles */
        .star-rating-container {
            padding: 20px;
            background: linear-gradient(135deg, rgba(248, 249, 255, 0.8) 0%, rgba(255, 245, 245, 0.8) 100%);
            border-radius: 15px;
            border: 2px solid rgba(240, 240, 240, 0.6);
            backdrop-filter: blur(5px);
        }

        .star-wrapper {
            transition: transform 0.2s ease;
        }

        .star-input {
            display: none;
        }

        .star-label {
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .star-svg {
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .star-fill {
            transform: scale(0);
            transform-origin: center;
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .star-pulse {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 193, 7, 0.3) 0%, transparent 70%);
            scale: 0;
            transition: scale 0.3s ease;
        }

        /* Hover Effects */
        .star-label:hover {
            transform: scale(1.1);
        }

        .star-label:hover .star-svg {
            filter: drop-shadow(0 4px 8px rgba(255, 193, 7, 0.3));
        }

        .star-label:hover .star-pulse {
            scale: 1;
            animation: pulse 0.6s ease-out;
        }

        /* Selected State */
        .star-wrapper.filled .star-fill {
            transform: scale(1);
        }

        .star-wrapper.filled .star-label {
            transform: scale(1.05);
        }

        .star-wrapper.filled .star-svg {
            filter: drop-shadow(0 4px 12px rgba(255, 193, 7, 0.5));
        }

        /* Hover effect - show preview of rating */
        .star-rating:hover .star-wrapper.hover-preview .star-fill {
            transform: scale(1);
            opacity: 0.7;
        }

        .star-rating:hover .star-wrapper.hover-preview .star-svg {
            filter: drop-shadow(0 2px 8px rgba(255, 193, 7, 0.3));
        }

        /* Animations */
        @keyframes pulse {
            0% { scale: 0; opacity: 1; }
            100% { scale: 1.5; opacity: 0; }
        }

        @keyframes starPop {
            0% { transform: scale(0) rotate(0deg); }
            50% { transform: scale(1.2) rotate(180deg); }
            100% { transform: scale(1) rotate(360deg); }
        }

        .star-wrapper.filled .star-fill {
            animation: starPop 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        /* Form Enhancements */
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .star-svg {
                width: 35px;
                height: 35px;
            }

            .star-pulse {
                width: 45px;
                height: 45px;
            }

            .btn-close {
                width: 30px;
                height: 30px;
            }

            .btn-close i {
                font-size: 12px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ratingInputs = document.querySelectorAll('input[name="rating"]');
            const ratingText = document.getElementById('ratingText');
            const commentTextarea = document.querySelector('textarea[name="comment"]');
            const charCount = document.getElementById('charCount');
            const submitBtn = document.getElementById('submitBtn');

            const ratingMessages = {
                1: "😞 Sangat Tidak Puas",
                2: "😐 Tidak Puas",
                3: "😊 Cukup Puas",
                4: "😃 Puas",
                5: "🤤 Ewe Ewe Ewe"
            };

            // Handle star rating
            ratingInputs.forEach((input, index) => {
                const starWrapper = input.parentElement;

                // Handle click events
                input.addEventListener('change', function() {
                    const value = parseInt(this.value);

                    // Remove all filled classes first
                    document.querySelectorAll('.star-wrapper').forEach(wrapper => {
                        wrapper.classList.remove('filled');
                    });

                    // Add filled class to stars up to selected rating
                    for (let i = 0; i < value; i++) {
                        document.querySelectorAll('.star-wrapper')[i].classList.add('filled');
                    }

                    ratingText.textContent = ratingMessages[value];
                    ratingText.style.color = value <= 2 ? '#dc3545' : value <= 3 ? '#ffc107' : '#28a745';
                    submitBtn.disabled = false;
                    submitBtn.classList.add('btn-pulse');

                    // Add sparkle effect to selected star
                    createSparkles(this.nextElementSibling);
                });

                // Handle hover preview
                starWrapper.addEventListener('mouseenter', function() {
                    if (!document.querySelector('input[name="rating"]:checked')) {
                        const hoverValue = parseInt(input.value);

                        // Remove all hover previews first
                        document.querySelectorAll('.star-wrapper').forEach(wrapper => {
                            wrapper.classList.remove('hover-preview');
                        });

                        // Add hover preview to stars up to hovered rating
                        for (let i = 0; i < hoverValue; i++) {
                            document.querySelectorAll('.star-wrapper')[i].classList.add('hover-preview');
                        }
                    }
                });
            });

            // Remove hover preview when leaving star rating area
            document.querySelector('.star-rating').addEventListener('mouseleave', function() {
                document.querySelectorAll('.star-wrapper').forEach(wrapper => {
                    wrapper.classList.remove('hover-preview');
                });
            });

            // Character counter
            if (commentTextarea) {
                commentTextarea.addEventListener('input', function() {
                    const length = this.value.length;
                    charCount.textContent = length;
                    charCount.style.color = length > 450 ? '#dc3545' : '#6c757d';

                    if (length > 500) {
                        this.value = this.value.substring(0, 500);
                        charCount.textContent = 500;
                    }
                });
            }

            // Sparkle effect function
            function createSparkles(element) {
                for (let i = 0; i < 6; i++) {
                    setTimeout(() => {
                        const sparkle = document.createElement('div');
                        sparkle.innerHTML = '✨';
                        sparkle.style.cssText = `
                            position: absolute;
                            font-size: 12px;
                            pointer-events: none;
                            animation: sparkleFloat 1s ease-out forwards;
                            left: ${Math.random() * 100}%;
                            top: ${Math.random() * 100}%;
                        `;
                        element.appendChild(sparkle);

                        setTimeout(() => sparkle.remove(), 1000);
                    }, i * 100);
                }
            }

            // Add sparkle animation CSS
            const sparkleStyle = document.createElement('style');
            sparkleStyle.textContent = `
                @keyframes sparkleFloat {
                    0% { transform: translateY(0) scale(0); opacity: 1; }
                    100% { transform: translateY(-30px) scale(1); opacity: 0; }
                }
                .btn-pulse {
                    animation: btnPulse 0.6s ease-out;
                }
                @keyframes btnPulse {
                    0% { transform: scale(1); }
                    50% { transform: scale(1.05); }
                    100% { transform: scale(1); }
                }
            `;
            document.head.appendChild(sparkleStyle);

            // Modal timing logic
            const loginTime = new Date("{{ session('login_time') }}");
            const now = new Date();
            const diffMs = now - loginTime;
            const diffMinutes = diffMs / 1000 / 60;

            if (diffMinutes >= 5) {
                showRatingModal();
            } else {
                const waitMs = (5 - diffMinutes) * 60 * 1000;
                setTimeout(() => {
                    showRatingModal();
                }, waitMs);
            }

            function showRatingModal() {
                const ratingModal = new bootstrap.Modal(document.getElementById('ratingModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                ratingModal.show();

                // Add entrance animation
                const modalDialog = document.querySelector('#ratingModal .modal-dialog');
                modalDialog.style.animation = 'modalSlideIn 0.5s ease-out';
            }

            // Add modal entrance animation
            const modalStyle = document.createElement('style');
            modalStyle.textContent = `
                @keyframes modalSlideIn {
                    0% { transform: translateY(-50px) scale(0.8); opacity: 0; }
                    100% { transform: translateY(0) scale(1); opacity: 1; }
                }
            `;
            document.head.appendChild(modalStyle);
        });
    </script>
@endif
