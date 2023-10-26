`use strict`;
console.log("Sanity check");

document.getElementById("imageForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData();
  const fileInput = document.getElementById("imageInput");

  formData.append("image", fileInput.files[0]);

  fetch("index.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        console.log(data.path);
        document.getElementById(
          "result"
        ).innerHTML = `<img src="${data.path}" alt="Uploaded Image">`;
      } else {
        document.getElementById("result").innerHTML = "Image upload failed.";
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      document.getElementById("result").innerHTML = "An error occurred.";
    });
});
