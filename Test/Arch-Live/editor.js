document.addEventListener("input", function () {
    let pages = document.querySelectorAll(".page");
    pages.forEach((page, index) => {
        if (page.scrollHeight > page.clientHeight) {
            let newPage = document.createElement("div");
            newPage.setAttribute("contenteditable", "true");
            newPage.classList.add("page");
            document.getElementById("editor-container").appendChild(newPage);
        }
    });
});
