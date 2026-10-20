document.addEventListener("DOMContentLoaded", function () {

  //Chi tiết thanh toán
  document.addEventListener("click", function (e) {
    const viewBtn = e.target.closest(".action-view");
    if (viewBtn) {
      e.preventDefault();
      Swal.fire({
        title:
          '<div style="font-size: 18px; font-weight: 600; text-align: left; color: #0f172a; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-circle-info" style="color: #3b82f6;"></i> Chi Tiết Thanh Toán</div>',
        html: `
                <div style="text-align: left; font-size: 14px; line-height: 1.8; color: #334155; border-top: 1px solid #e2e8f0; padding-top: 15px; margin-top: 5px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Mã giao dịch:</span>
                        <strong style="color: #0f172a;">PAY-9825</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Tên khách hàng:</span>
                        <strong style="color: #0f172a;">Phạm Minh Tuấn</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Số điện thoại:</span>
                        <strong style="color: #0f172a;">0988 123 456</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Số tiền:</span>
                        <strong style="color: #2563eb; font-size: 16px;">850.000 đ</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Phương thức thanh toán:</span>
                        <strong style="color: #0f172a;">Thanh toán COD</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; align-items: center;">
                        <span style="color: #64748b;">Mã đối soát / Ref:</span>
                        <span style="font-family: monospace; background: #f1f5f9; color: #334155; padding: 3px 8px; border-radius: 4px; font-size: 12px; border: 1px solid #e2e8f0;">COD-SHIPPER-442</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                        <span style="color: #64748b;">Trạng thái:</span>
                        <strong style="color: #0369a1;">Thanh toán khi nhận hàng (COD)</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <span style="color: #64748b;">Ghi chú:</span>
                        <span style="font-style: italic; color: #475569;">Chờ giao hàng và thu tiền tại nhà</span>
                    </div>
                </div>
            `,
        confirmButtonText: "Đóng",
        confirmButtonColor: "#3b82f6",
        width: "550px",
        padding: "20px",
      });
    }
  });

  //xác nhan thanh toan
  document.addEventListener("click", function (e) {
    const collectedBtn = e.target.closest(".action-collected");
    if (collectedBtn) {
      e.preventDefault();

      Swal.fire({
        title:
          '<div style="font-size: 18px; font-weight: 600; text-align: left; color: #0f172a; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Xác Nhận Thanh Toán</div>',
        html: `
                <div style="text-align: left; font-size: 14px; line-height: 1.6; color: #334155; margin-top: 10px;">
                    <p style="margin-bottom: 15px;">Xác nhận đã thu tiền mặt <strong style="color: #0f172a;">850.000 đ</strong> từ khách hàng <strong style="color: #0f172a;">Phạm Minh Tuấn</strong> thông qua shipper?</p>
                    <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 5px;">Ghi chú xác nhận / Mã đối soát:</label>
                    <textarea id="swal-input-note" class="swal2-textarea" placeholder="Nhập ghi chú thu tiền COD hoặc mã ngân hàng..." style="width: 100%; height: 80px; margin: 0 0 15px 0; font-size: 13px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"></textarea>
                </div>
            `,
        confirmButtonText: "Xác Nhận Duyệt",
        confirmButtonColor: "#059669",
        width: "520px",
        padding: "20px",
      }).then((result) => {
        if (result.isConfirmed) {
          const noteValue = document.getElementById("swal-input-note").value;

          Swal.fire({
            icon: "success",
            title: "Thành công!",
            text: "Đã cập nhật trạng thái thu tiền.",
            timer: 1500,
            showConfirmButton: false,
          });
        }
      });
    }
  });
  // 2. Xử lý khi bấm nút "Duyệt giao dịch" (Chuyển khoản)
document.addEventListener("click", function (e) {
  const approveBtn = e.target.closest(".action-approve");
  if (approveBtn) {
    e.preventDefault();

    Swal.fire({
      title:
        '<div style="font-size: 18px; font-weight: 600; text-align: left; color: #0f172a; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-building-columns" style="color: #2563eb;"></i> Xác Nhận Duyệt Chuyển Khoản</div>',
      html: `
        <div style="text-align: left; font-size: 14px; line-height: 1.6; color: #334155; margin-top: 10px;">
            <p style="margin-bottom: 15px;">Xác nhận duyệt giao dịch chuyển khoản số tiền <strong style="color: #0f172a;">2.300.000 đ</strong> của khách hàng <strong style="color: #0f172a;">Trần Thị B</strong>?</p>
            <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 5px;">Mã giao dịch ngân hàng / Đối soát (Ref):</label>
            <input type="text" id="swal-input-ref" class="swal2-input" placeholder="Nhập mã giao dịch ngân hàng (VD: VCB-987654)..." style="width: 100%; margin: 0 0 15px 0; font-size: 13px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
        </div>
      `,
      confirmButtonText: "Xác Nhận Duyệt",
      confirmButtonColor: "#2563eb",
      width: "520px",
      padding: "20px",
    }).then((result) => {
      if (result.isConfirmed) {
        const refValue = document.getElementById("swal-input-ref").value;

        Swal.fire({
          icon: "success",
          title: "Thành công!",
          text: "Giao dịch chuyển khoản đã được duyệt.",
          timer: 1500,
          showConfirmButton: false,
        });
      }
    });
  }
});
    //xu ly hoàn tiền 
    document.addEventListener("click", function (e) {
      const refundBtn = e.target.closest(".action-refund");
      if (refundBtn) {
        e.preventDefault();

        Swal.fire({
          title:
            '<div style="font-size: 18px; font-weight: 600; text-align: left; color: #0f172a; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-rotate-left" style="color: #e11d48;"></i> Yêu Cầu Hoàn Tiền</div>',
          html: `
        <div style="text-align: left; font-size: 14px; line-height: 1.6; color: #334155; margin-top: 10px;">
            <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 5px;">Số tiền hoàn:</label>
            <div style="font-size: 18px; font-weight: bold; color: #0f172a; background: #f8fafc; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 15px;">
                1.500.000 đ
            </div>

            <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 5px;">Lý do hoàn tiền (*):</label>
            <textarea id="swal-input-refund-reason" class="swal2-textarea" placeholder="Khách trả hàng, thanh toán nhầm, hủy đơn..." style="width: 100%; height: 90px; margin: 0 0 15px 0; font-size: 13px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"></textarea>
        </div>
      `,
          showCancelButton: true,
          confirmButtonText: "Xác Nhận Hoàn Tiền",
          confirmButtonColor: "#e11d48",
          width: "520px",
          padding: "20px",
         
        }).then((result) => {
          if (result.isConfirmed) {
            const reasonValue = document.getElementById(
              "swal-input-refund-reason",
            ).value;

            Swal.fire({
              icon: "success",
              title: "Thành công!",
              text: "Yêu cầu hoàn tiền đã được gửi.",
              timer: 1500,
              showConfirmButton: false,
            });
          }
        });
      }
    });
    document.addEventListener("click", function (e) {
      const refundBtn = e.target.closest(".action-refund");
      if (refundBtn) {
        e.preventDefault();

        Swal.fire({
          title:
            '<div style="font-size: 18px; font-weight: 600; text-align: left; color: #0f172a; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-rotate-left" style="color: #e11d48;"></i> Yêu Cầu Hoàn Tiền</div>',
          html: `
        <div style="text-align: left; font-size: 14px; line-height: 1.6; color: #334155; margin-top: 10px;">
            <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 5px;">Số tiền hoàn:</label>
            <div style="font-size: 18px; font-weight: bold; color: #0f172a; background: #f8fafc; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 15px;">
                1.500.000 đ
            </div>

            <label style="display: block; font-size: 13px; font-weight: 500; color: #64748b; margin-bottom: 5px;">Lý do hoàn tiền (*):</label>
            <textarea id="swal-input-refund-reason" class="swal2-textarea" placeholder="Khách trả hàng, thanh toán nhầm, hủy đơn..." style="width: 100%; height: 90px; margin: 0 0 15px 0; font-size: 13px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"></textarea>
        </div>
      `,
          confirmButtonText: "Xác Nhận Hoàn Tiền",
          confirmButtonColor: "#e11d48",
          width: "520px",
          padding: "20px",
        }).then((result) => {
            //kt bam nut xac nhan
          if (result.isConfirmed) {
            const reasonValue = document.getElementById(
              "swal-input-refund-reason",
            ).value;
            Swal.fire({
              icon: "success",
              title: "Thành công!",
              text: "Yêu cầu hoàn tiền đã được gửi.",
              timer: 1500,
              showConfirmButton: false,
            });
          }
        });
      }
    });
});
