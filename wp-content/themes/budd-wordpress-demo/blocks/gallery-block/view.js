import ACFBlock from '../../assets/js/utils/blocks';
import Swiper from 'swiper';

/**
 * GalleryBlockView - Client-side functionality for the Gallery Block.
 */
class GalleryBlockView {
    constructor(block) {
        this.block = block;
        this.init();
    }

    static getName() {
        return 'gallery-block';
    }

    init() {
        this.initializeSlider();
    }

    initializeSlider() {
        const sliderContainer = this.block.querySelector('.js-gallery-slider');
        if (!sliderContainer) {
            return;
        }

        const prevButton = this.block.querySelector('.js-gallery-prev-button');
        const nextButton = this.block.querySelector('.js-gallery-next-button');
        const pagination = this.block.querySelector('.js-gallery-pagination');

        this.swiper = new Swiper(sliderContainer, {
            navigation: {
                prevEl: prevButton,
                nextEl: nextButton,
            },
            pagination: {
                el: pagination,
                clickable: true,
            },
            spaceBetween: 16,
            slidesPerView: 1.25,
            loop: true,
            centeredSlides: true,
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    centeredSlides: false,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 3,
                    centeredSlides: false,
                    spaceBetween: 24,
                },
            },
        });
    }
}

new ACFBlock(GalleryBlockView);