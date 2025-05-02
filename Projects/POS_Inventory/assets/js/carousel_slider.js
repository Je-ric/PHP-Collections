// POS - Products
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('category-container');
    const carouselContent = container.querySelector('.carousel-content');
    const prev = document.getElementById('carousel-prev');
    const next = document.getElementById('carousel-next');
    
    let offset = 0;
    const cardWidth = 150; 
    const step = cardWidth + 20;  
    
    prev.addEventListener('click', () => {
        offset = Math.max(0, offset - step); 
        carouselContent.style.transform = `translateX(-${offset}px)`; 
    });
    
    next.addEventListener('click', () => {
        const maxOffset = carouselContent.scrollWidth - container.clientWidth;
        offset = Math.min(maxOffset, offset + step); 
        carouselContent.style.transform = `translateX(-${offset}px)`; 
    });
});