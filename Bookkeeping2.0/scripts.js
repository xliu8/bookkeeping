document.addEventListener("DOMContentLoaded", function () {
  // 你的所有代码都放在这里

  document.getElementById("DateID").addEventListener("change", function () {
    if (this.value === "createNew") {
      window.location.href = "create_new_Date_page.php";
    }
  });

  document.getElementById("AccountID").addEventListener("change", function () {
    if (this.value === "createNew") {
      window.location.href = "create_new_Account_page.php";
    }
  });
});
function confirmDelete(nameId) {
  var r = confirm("Are you sure you want to delete this record?");
  if (r == true) {
    return true;
  } else {
    return false; // 这将阻止<a>标签的默认动作，即阻止跳转到href的URL
  }
}
