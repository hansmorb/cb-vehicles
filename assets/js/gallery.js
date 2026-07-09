var slideIndex = 1;

function initSlides() {
	showSlides(slideIndex);
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", initSlides);
} else {
	initSlides();
}

// Next/previous controls
function plusSlides(n) {
	showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
	showSlides(slideIndex = n);
}

function showSlides(n) {
	var i;
	var slides = document.getElementsByClassName("itemgallery");
	var dots = document.getElementsByClassName("dot");
	if (slides.length === 0) {
		return;
	}
	if (n > slides.length) {slideIndex = 1}
	if (n < 1) {slideIndex = slides.length}
	for (i = 0; i < slides.length; i++) {
		slides[i].style.display = "none";
	}
	for (i = 0; i < dots.length; i++) {
		dots[i].className = dots[i].className.replace(" active", "");
	}
	slides[slideIndex-1].style.display = "block";
	if (dots[slideIndex-1]) {
		dots[slideIndex-1].className += " active";
	}
}
