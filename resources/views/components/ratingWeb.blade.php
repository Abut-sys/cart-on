@if (auth()->check() && session('login_time'))
    <meta name="login-time" content="{{ session('login_time') }}">

    <!-- Enhanced Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="ratingForm" method="POST" action="{{ route('rating.store') }}">
                @csrf
                <input type="hidden" name="information_id" value="{{ $information->id ?? 1 }}">
                <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                    <!-- Close Button -->
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                        aria-label="Close" style="z-index: 1050;">
                        <i class="fas fa-times"></i>
                    </button>

                    <div class="modal-body p-4">
                        <!-- Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success border-0 rounded-3 mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        <!-- Error Messages -->
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
                                            <input type="radio" id="star{{ $i }}" name="rating"
                                                value="{{ $i }}" class="star-input"
                                                {{ old('rating') == $i ? 'checked' : '' }}>
                                            <label for="star{{ $i }}"
                                                class="star-label position-relative d-block"
                                                title="{{ $i }} bintang">
                                                <svg class="star-svg" width="40" height="40" viewBox="0 0 24 24"
                                                    fill="none">
                                                    <path class="star-path"
                                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"
                                                        stroke="#e0e0e0" stroke-width="1.5" fill="#f5f5f5" />
                                                    <path class="star-fill"
                                                        d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"
                                                        fill="#ffc107" />
                                                </svg>
                                                <div class="star-pulse"></div>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                <div class="rating-text mt-3">
                                    <span id="ratingText" class="text-muted fst-italic">
                                        {{ old('rating') ? 'Rating: ' . old('rating') . ' bintang' : 'Pilih rating Anda' }}
                                    </span>
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
                                <textarea name="comment" class="form-control border-2 rounded-3 shadow-sm @error('comment') is-invalid @enderror"
                                    rows="4" placeholder="Ceritakan pengalaman Anda... Saran dan kritik sangat membantu kami untuk berkembang!"
                                    style="resize: none; padding-top: 12px; padding-bottom: 12px;">{{ old('comment') }}</textarea>
                                <div class="position-absolute bottom-0 end-0 p-2">
                                    <small class="text-muted">
                                        <span id="charCount">{{ strlen(old('comment', '')) }}</span>/500
                                    </small>
                                </div>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-4 pt-2">
                        <div class="d-grid w-100">
                            <button type="submit" class="btn btn-lg rounded-3 shadow-sm"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white;"
                                {{ !old('rating') ? 'disabled' : '' }} id="submitBtn">
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

    <link href="{{ asset('pemai/css/rating.css') }}" rel="stylesheet">
    <script src="{{ asset('js/rating.js') }}"></script>
@endif
