document.addEventListener('DOMContentLoaded', function() {
    let nextBtn = document.getElementById('next');
    let prevBtn = document.getElementById('prev');
    let slider = document.querySelector('.slider');
    let sliderList = document.querySelector('.slider .list');
    let thumbnail = document.querySelector('.thumbnail');
    
    if (!nextBtn || !prevBtn) {
        console.error('Navigation buttons not found');
        return;
    }

    function moveSlider(direction) {
        let sliderItems = document.querySelectorAll('.slider .list .item');
        let thumbnailItems = document.querySelectorAll('.thumbnail .item');
        
        sliderItems.forEach(item => item.classList.remove('active'));

        if (direction === "next") {
            sliderList.appendChild(sliderItems[0]);
            thumbnail.appendChild(thumbnailItems[0]);
            sliderItems = document.querySelectorAll('.slider .list .item'); // Update the list
            sliderItems[0].classList.add('active');
            slider.classList.add('next');
        } else {
            sliderList.prepend(sliderItems[sliderItems.length - 1]);
            thumbnail.prepend(thumbnailItems[thumbnailItems.length - 1]);
            sliderItems = document.querySelectorAll('.slider .list .item'); // Update the list
            sliderItems[0].classList.add('active');
            slider.classList.add('prev');
        }

        // Remove animation class
        setTimeout(() => {
            slider.classList.remove('next');
            slider.classList.remove('prev');
        }, 1000);
    }

    nextBtn.addEventListener('click', () => moveSlider('next'));
    prevBtn.addEventListener('click', () => moveSlider('prev'));

    // Initialize the first item as active
    document.querySelector('.slider .list .item').classList.add('active');
});
