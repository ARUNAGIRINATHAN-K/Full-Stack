let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  const slides = document.getElementsByClassName("mySlides");
  const dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  if(slides.length > 0) {
    slides[slideIndex-1].style.display = "block";
    if(dots.length > 0) dots[slideIndex-1].className += " active";
  }
  setTimeout(showSlides, 5000); // Change image every 3 seconds
}