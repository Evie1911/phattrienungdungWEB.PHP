document.addEventListener("DOMContentLoaded", function () {
  //o nhap them
  document.querySelectorAll(".nut-them").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      // Lấy thông tin cấu hình trực tiếp từ thuộc tính data
      const modalTitle = this.dataset.title;
      const inputLabel = this.dataset.label;
      const successMsg = this.dataset.success;

      Swal.fire({
        title: modalTitle,
        input: "text",
        inputLabel: inputLabel,
        inputPlaceholder: `Nhập ${inputLabel.toLowerCase()}...`,
        showCancelButton: true,
        confirmButtonText: "Lưu lại",
        cancelButtonText: "Hủy bỏ",
        confirmButtonColor: "#2ecc71",
        cancelButtonColor: "#f19066",
        inputValidator: (value) => {
          if (!value || !value.trim()) {
            return `Vui lòng nhập ${inputLabel.toLowerCase()}!`;
          }
        },
      }).then((result) => {
        if (result.isConfirmed) {
          const giaTriMoi = result.value.trim();
          console.log("Giá trị người dùng nhập:", giaTriMoi);

          Swal.fire("Thành công!", successMsg, "success");
        }
      });
    });
  });
  // Nút lưu dữ liệu
  const nutLuu = document.querySelector(".nutLuu");
  if (nutLuu) {
    nutLuu.addEventListener("click", function (e) {
      e.preventDefault();

      const dataType = this.dataset.type || "dữ liệu";
      const redirectUrl = this.dataset.redirect || "#";

      Swal.fire({
        title: "Xác nhận lưu dữ liệu?",
        text: `Bạn có chắc chắn muốn lưu ${dataType} này không?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Đồng ý",
        cancelButtonText: "Hủy bỏ",
        confirmButtonColor: "#2ecc71",
        cancelButtonColor: "#e74c3c",
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: "Thành công!",
            text: `${dataType.charAt(0).toUpperCase() + dataType.slice(1)} đã được lưu thành công.`,
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
  //nút xóa
  const deleteButtons = document.querySelectorAll(".nutxoa");
  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const row = this.closest("tr");
      const dataType = this.dataset.type || "mục";

      Swal.fire({
        title: "Cảnh Báo",
        text: `Bạn có chắc chắn là muốn xóa ${dataType} này?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e74c3c",
        cancelButtonColor: "#95a5a6",
        confirmButtonText: "Đồng ý xóa",
        cancelButtonText: "Hủy",
      }).then((result) => {
        if (result.isConfirmed) {
          if (row) {
            row.remove();
          }
          Swal.fire(
            "Đã xóa!",
            `${dataType.charAt(0).toUpperCase() + dataType.slice(1)} đã được xóa thành công.`,
            "success",
          );
        }
      });
    });
  });
});
