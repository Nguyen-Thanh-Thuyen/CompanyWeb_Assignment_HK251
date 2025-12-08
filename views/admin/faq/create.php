<div class="page-header">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">Thêm câu hỏi thường gặp (FAQ)</h2>
      </div>
    </div>
  </div>
</div>

<div class="page-body">
  <div class="container-xl">
    <div class="card">
      <div class="card-body">
        <form action="" method="POST">
            
            <div class="mb-3">
                <label class="form-label required">Câu hỏi</label>
                <input type="text" class="form-control" name="question" required placeholder="Nhập nội dung câu hỏi...">
            </div>
            
            <div class="mb-3">
                <label class="form-label required">Câu trả lời</label>
                <textarea class="form-control" name="answer" rows="6" required placeholder="Nhập nội dung câu trả lời..."></textarea>
            </div>
            
            <div class="card-footer text-end bg-transparent px-0 pb-0 mt-3 border-top-0">
                <a href="index.php?page=admin_faq_list" class="btn btn-link link-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary ms-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                       <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                       <line x1="12" y1="5" x2="12" y2="19"></line>
                       <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Lưu câu hỏi
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
