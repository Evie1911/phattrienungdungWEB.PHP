document.addEventListener("DOMContentLoaded", function () {
  //Nút hủy bỏ thao tác
  const nutHuy = document.querySelector(".nutHuy");
  if (nutHuy) {
    nutHuy.addEventListener("click", function (e) {
      e.preventDefault();

      const dataType = this.dataset.type || "dữ liệu";
      const redirectUrl = this.dataset.redirect || "#"; // Lấy đường dẫn từ HTML

      Swal.fire({
        title: "Xác nhận hủy",
        text: `Bạn có chắc chắn muốn hủy thao tác với ${dataType} này không?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Đồng ý",
        cancelButtonText: "Tiếp tục sửa",
        confirmButtonColor: "#f19066",
        cancelButtonColor: "#2ecc71",
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: "Thành công!",
            text: `Thao tác ${dataType} đã được hủy bỏ.`,
            icon: "success",
            confirmButtonText: "OK",
            confirmButtonColor: "#2ecc71",
          }).then((successResult) => {
            if (successResult.isConfirmed) {
              window.location.href = redirectUrl;
            }
          });
        }
      });
    });
  }
 
});
