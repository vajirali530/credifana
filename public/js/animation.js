gsap.registerPlugin(ScrollTrigger);

$(document).ready(function () {
    
    // Animating left and right block of a container
    if ($('.left-block')[0] && $('.right-block')[0]) {
        gsap.utils.toArray('.left-block').forEach(leftBlock => {
            let tl = gsap.timeline({
                scrollTrigger: {
                    trigger: leftBlock,
                    markers: false,
                    start: 'top 75%',
                }
            });
            tl.from(leftBlock, {
                duration: 0.5,
                x: -200,
                opacity: 0
            });
        });
        gsap.utils.toArray('.right-block').forEach(rightBlock => {
            let tl = gsap.timeline({
                scrollTrigger: {
                    trigger: rightBlock,
                    start: 'top 75%',
                }
            });
            tl.from(rightBlock, {
                duration: 0.5,
                x: 200,
                opacity: 0
            });
        });
    }
    
});