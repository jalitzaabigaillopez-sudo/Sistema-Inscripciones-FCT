const fulImgBox = document.getElementById("fulImgBox"),
      fulImg = document.getElementById("fulImg"),
      imgGallery = document.querySelectorAll(".img-gallery img");

let currentImgIndex;

function openFulImg(reference, index) {
    fulImgBox.style.display = "flex";
    fulImg.src = reference;
    currentImgIndex = index;
}

function closeImg() {
    fulImgBox.style.display = "none";
}

function prevImg() {
    currentImgIndex = (currentImgIndex > 0) ? currentImgIndex - 1 : imgGallery.length - 1;
    fulImg.src = imgGallery[currentImgIndex].src;
}

function nextImg() {
    currentImgIndex = (currentImgIndex < imgGallery.length - 1) ? currentImgIndex + 1 : 0;
    fulImg.src = imgGallery[currentImgIndex].src;
}

imgGallery.forEach((img, index) => {
    img.addEventListener("click", () => openFulImg(img.src, index));
});
