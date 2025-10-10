/**
 * Dynamic Carousel Widget JavaScript
 * File: dynamic-carousel-script.js
 */

(function($) {
    'use strict';

    class DynamicCarousel {
        constructor(element) {
            this.$wrapper = $(element);
            this.$container = this.$wrapper.find('.dynamic-carousel-container');
            this.$track = this.$wrapper.find('.dynamic-carousel-track');
            this.$slides = this.$wrapper.find('.dynamic-carousel-slide');
            this.$prevBtn = this.$wrapper.find('.carousel-arrow-left');
            this.$nextBtn = this.$wrapper.find('.carousel-arrow-right');
            this.$dots = this.$wrapper.find('.carousel-dot');
            
            this.settings = this.$wrapper.data('settings') || {};
            this.currentIndex = 0;
            this.currentOffset = 0;
            this.isDragging = false;
            this.startX = 0;
            this.currentX = 0;
            this.autoplayInterval = null;
            this.slidePositions = [];
            
            this.init();
        }

        init() {
            this.calculateSlidePositions();
            this.bindEvents();
            this.updateCarousel();

            // Apply transition speed from settings
            if (this.settings.transitionSpeed) {
                this.$track.css('transition', `transform ${this.settings.transitionSpeed}ms ease`);
            }

            if (this.settings.autoplay) {
                this.startAutoplay();
            }

            // Recalculate on window resize with debounce
            const resizeHandler = this.debounce(() => {
                this.calculateSlidePositions();
                this.updateCarousel(false);
            }, 250);

            $(window).on('resize', resizeHandler);
        }

        calculateSlidePositions() {
            this.slidePositions = [];
            let cumulativeOffset = 0;
            
            this.$slides.each((index, slide) => {
                const $slide = $(slide);
                const slideWidth = $slide.outerWidth(true); // Include margin
                
                this.slidePositions.push({
                    index: index,
                    offset: cumulativeOffset,
                    width: slideWidth
                });
                
                cumulativeOffset += slideWidth;
            });
            
            this.totalWidth = cumulativeOffset;
            this.containerWidth = this.$container.width();
        }

        bindEvents() {
            // Navigation buttons
            this.$prevBtn.on('click', () => this.prev());
            this.$nextBtn.on('click', () => this.next());
            
            // Pagination dots
            this.$dots.on('click', (e) => {
                const index = $(e.currentTarget).data('slide-index');
                this.goToSlide(index);
            });
            
            // Touch/Mouse drag events
            this.$container.on('mousedown touchstart', (e) => this.handleDragStart(e));
            $(document).on('mousemove touchmove', (e) => this.handleDragMove(e));
            $(document).on('mouseup touchend', () => this.handleDragEnd());
            
            // Prevent default drag on images
            this.$slides.find('img').on('dragstart', (e) => e.preventDefault());
            
            // Keyboard navigation
            this.$wrapper.attr('tabindex', '0');
            this.$wrapper.on('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    this.prev();
                } else if (e.key === 'ArrowRight') {
                    this.next();
                }
            });
            
            // Pause autoplay on hover
            if (this.settings.autoplay) {
                this.$wrapper.on('mouseenter', () => this.stopAutoplay());
                this.$wrapper.on('mouseleave', () => this.startAutoplay());
            }
        }

        handleDragStart(e) {
            this.isDragging = true;
            this.startX = this.getPositionX(e);
            this.currentX = this.startX;
            this.$wrapper.addClass('grabbing');
            this.$track.css('transition', 'none');
            
            if (this.settings.autoplay) {
                this.stopAutoplay();
            }
        }

        handleDragMove(e) {
            if (!this.isDragging) return;
            
            e.preventDefault();
            this.currentX = this.getPositionX(e);
            const diff = this.currentX - this.startX;
            const newOffset = this.currentOffset - diff;
            
            // Add resistance at boundaries
            let finalOffset = newOffset;
            if (newOffset < 0) {
                finalOffset = newOffset * 0.3; // Resistance at start
            } else if (newOffset > this.totalWidth - this.containerWidth) {
                const excess = newOffset - (this.totalWidth - this.containerWidth);
                finalOffset = (this.totalWidth - this.containerWidth) + (excess * 0.3);
            }
            
            this.$track.css('transform', `translateX(-${finalOffset}px)`);
        }

        handleDragEnd() {
            if (!this.isDragging) return;
            
            this.isDragging = false;
            this.$wrapper.removeClass('grabbing');
            this.$track.css('transition', '');
            
            const diff = this.currentX - this.startX;
            const threshold = 50; // Minimum drag distance to trigger slide change
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    this.prev();
                } else {
                    this.next();
                }
            } else {
                this.updateCarousel();
            }
            
            if (this.settings.autoplay) {
                this.startAutoplay();
            }
        }

        getPositionX(e) {
            return e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
        }

        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.updateCarousel();
            } else if (this.settings.loop) {
                this.currentIndex = this.$slides.length - 1;
                this.updateCarousel();
            }
        }

        next() {
            // Find the next slide that would be visible
            const nextPosition = this.findNextVisibleSlide();
            
            if (nextPosition !== null) {
                this.currentIndex = nextPosition;
                this.updateCarousel();
            } else if (this.settings.loop) {
                this.currentIndex = 0;
                this.updateCarousel();
            }
        }

        findNextVisibleSlide() {
            const currentSlidePosition = this.slidePositions[this.currentIndex];
            const nextOffset = currentSlidePosition.offset + currentSlidePosition.width;
            
            // Find the slide at the next offset position
            for (let i = this.currentIndex + 1; i < this.slidePositions.length; i++) {
                const slidePos = this.slidePositions[i];
                
                // Check if this slide would fit in the viewport
                if (slidePos.offset + slidePos.width <= this.totalWidth) {
                    return i;
                }
            }
            
            return null;
        }

        goToSlide(index) {
            if (index >= 0 && index < this.$slides.length) {
                this.currentIndex = index;
                this.updateCarousel();
            }
        }

        updateCarousel(animate = true) {
            const slidePosition = this.slidePositions[this.currentIndex];

            if (!slidePosition) return;

            // Calculate offset to show current slide
            let offset = slidePosition.offset;

            // Ensure we don't scroll past the end
            const maxOffset = Math.max(0, this.totalWidth - this.containerWidth);
            offset = Math.min(offset, maxOffset);
            offset = Math.max(0, offset);

            this.currentOffset = offset;

            // Apply transform with proper transition
            if (!animate) {
                this.$track.css('transition', 'none');
            } else {
                // Restore transition from settings
                const transitionSpeed = this.settings.transitionSpeed || 500;
                this.$track.css('transition', `transform ${transitionSpeed}ms ease`);
            }

            this.$track.css('transform', `translateX(-${offset}px)`);

            if (!animate) {
                // Force reflow and restore transition
                this.$track[0].offsetHeight;
                const transitionSpeed = this.settings.transitionSpeed || 500;
                this.$track.css('transition', `transform ${transitionSpeed}ms ease`);
            }

            // Update active dot
            this.$dots.removeClass('active');
            this.$dots.eq(this.currentIndex).addClass('active');

            // Update button states
            this.updateButtonStates();
        }

        updateButtonStates() {
            if (!this.settings.loop) {
                // Disable prev button at start
                if (this.currentIndex === 0) {
                    this.$prevBtn.prop('disabled', true);
                } else {
                    this.$prevBtn.prop('disabled', false);
                }
                
                // Disable next button at end
                const isAtEnd = this.currentOffset >= (this.totalWidth - this.containerWidth - 1);
                if (isAtEnd || this.currentIndex === this.$slides.length - 1) {
                    this.$nextBtn.prop('disabled', true);
                } else {
                    this.$nextBtn.prop('disabled', false);
                }
            } else {
                this.$prevBtn.prop('disabled', false);
                this.$nextBtn.prop('disabled', false);
            }
        }

        startAutoplay() {
            if (!this.settings.autoplay) return;
            
            this.stopAutoplay();
            this.autoplayInterval = setInterval(() => {
                this.next();
            }, this.settings.autoplaySpeed);
        }

        stopAutoplay() {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
                this.autoplayInterval = null;
            }
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        destroy() {
            this.stopAutoplay();
            this.$prevBtn.off('click');
            this.$nextBtn.off('click');
            this.$dots.off('click');
            this.$container.off('mousedown touchstart');
            $(document).off('mousemove touchmove');
            $(document).off('mouseup touchend');
            this.$wrapper.off('keydown mouseenter mouseleave');
            $(window).off('resize');
        }
    }

    // Initialize carousels
    function initCarousels() {
        $('.dynamic-carousel-wrapper').each(function() {
            if (!$(this).data('carousel-instance')) {
                const carousel = new DynamicCarousel(this);
                $(this).data('carousel-instance', carousel);
            }
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        initCarousels();
    });

    // Initialize on Elementor frontend
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/dynamic-carousel.default', function($scope) {
            const $carousel = $scope.find('.dynamic-carousel-wrapper');
            if ($carousel.length && !$carousel.data('carousel-instance')) {
                const carousel = new DynamicCarousel($carousel[0]);
                $carousel.data('carousel-instance', carousel);
            }
        });
    });

})(jQuery);