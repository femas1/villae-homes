document.addEventListener('DOMContentLoaded', function() {
  // Create lightbox elements
  const lightbox = document.createElement('div');
  lightbox.className = 'lightbox';
  lightbox.innerHTML = `
    <span class="close-lightbox">&times;</span>
    <div class="lightbox-content"></div>
    <div class="lightbox-nav">
      <button class="prev-btn">&#10094;</button>
      <button class="next-btn">&#10095;</button>
    </div>
  `;
  document.body.appendChild(lightbox);
  
  const images = document.querySelectorAll('.home_images img');
  const lightboxImg = document.querySelector('.lightbox-content');
  const closeBtn = document.querySelector('.close-lightbox');
  const prevBtn = document.querySelector('.prev-btn');
  const nextBtn = document.querySelector('.next-btn');
  let currentIndex = 0;
  
  // Open lightbox
  images.forEach((img, index) => {
    img.addEventListener('click', () => {
      currentIndex = index;
      updateLightboxImage();
      lightbox.style.display = 'flex';
      document.body.style.overflow = 'hidden'; // Prevent scrolling
    });
  });
  
  // Close lightbox
  closeBtn.addEventListener('click', () => {
    lightbox.style.display = 'none';
    document.body.style.overflow = 'auto';
  });
  
  // Navigation
  prevBtn.addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateLightboxImage();
  });
  
  nextBtn.addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % images.length;
    updateLightboxImage();
  });
  
  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (lightbox.style.display === 'flex') {
      if (e.key === 'ArrowLeft') {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateLightboxImage();
      } else if (e.key === 'ArrowRight') {
        currentIndex = (currentIndex + 1) % images.length;
        updateLightboxImage();
      } else if (e.key === 'Escape') {
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto';
      }
    }
  });
  
  function updateLightboxImage() {
    lightboxImg.innerHTML = '';
    const imgClone = images[currentIndex].cloneNode();
    imgClone.style.maxHeight = '90vh';
    imgClone.style.objectFit = 'contain';
    lightboxImg.appendChild(imgClone);
  }
  
  // Close when clicking outside image
  lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) {
      lightbox.style.display = 'none';
      document.body.style.overflow = 'auto';
    }
  });
});
