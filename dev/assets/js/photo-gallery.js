document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.wp-photo-slider').forEach(function (gallery) {
        new Splide(gallery, {
            type: 'loop',
            perPage: 1,
            gap: '1rem',
            pagination: true,
            arrows: true,
            lazyLoad: 'nearby'
        }).mount();
    });
});