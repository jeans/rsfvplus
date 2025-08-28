jQuery(document).ready(function($) {
    function isMEW() {
        return window.innerWidth < 768;
    }

    // Scroll-to-play for single posts
    if (rsfvSettings.scrollPlaySingleDesktop === 'on' && !isMEW()) {
        $('.rsfv-video-container video').each(function() {
            var video = this;
            $(window).on('scroll', function() {
                if ($(video).isInViewport()) {
                    video.play();
                } else {
                    video.pause();
                }
            });
        });
    }
    if (rsfvSettings.scrollPlaySingleMEW === 'on' && isMEW()) {
        $('.rsfv-video-container video').each(function() {
            var video = this;
            $(window).on('scroll', function() {
                if ($(video).isInViewport()) {
                    video.play();
                } else {
                    video.pause();
                }
            });
        });
    }

    // Play-on-mouseover in Lists Desktop
    if (rsfvSettings.mouseoverPlayListsDesktop === 'on' && !isMEW()) {
        $('.rsfv-video-list video').on('mouseenter', function() { this.play(); })
                                   .on('mouseleave', function() { this.pause(); });
    }

    // Scroll-to-play in Lists MEW
    if (rsfvSettings.scrollPlayListsMEW === 'on' && isMEW()) {
        $('.rsfv-video-list video').each(function() {
            var video = this;
            $(window).on('scroll', function() {
                if ($(video).isInViewport()) {
                    video.play();
                } else {
                    video.pause();
                }
            });
        });
    }

    // Autoplay on single post desktop
    if (rsfvSettings.autoplaySingleDesktop === 'on' && !isMEW()) {
        $('.rsfv-video-container video').each(function() { this.play(); });
    }
    if (rsfvSettings.autoplaySingleMEW === 'on' && isMEW()) {
        $('.rsfv-video-container video').each(function() { this.play(); });
    }

    // Show play button overlay (click to play)
    if (rsfvSettings.showPlayButtonLists === 'on') {
        $('.rsfv-play-btn').on('click', function() {
            var video = $(this).closest('.rsfv-poster-wrap').find('video').get(0);
            if (video.paused) video.play();
            else video.pause();
        });
    }
});

// Helper to check if element is in viewport
jQuery.fn.isInViewport = function() {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();
    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();
    return elementBottom > viewportTop && elementTop < viewportBottom;
};