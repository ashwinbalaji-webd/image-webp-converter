`use strict`;
console.log("Sanity check");

document.getElementById("imageForm").addEventListener("submit", function (e) {
  e.preventDefault();

  fetch("packages.json")
    .then((response) => response.json())
    .then((data) => {
      try {
        // console.log(Object.keys(data));
        if (Object.keys(data).length) {
          const url = "index.php";
          const postData = {
            "uploadDir": Object.keys(data)[0],
            "images": data[Object.keys(data)[0]],
          };

          const requestOptions = {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(postData),
          };

          fetch(url, requestOptions)
            .then((response) => response.json())
            .then((data) => console.log(data.paths));
        }
      } catch (error) {
        alert(error.message);
        console.log(error.message);
      }
    });
});
