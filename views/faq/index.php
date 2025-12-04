<div class="container my-5">
    <h2 class="text-center mb-5">Câu hỏi thường gặp (FAQ)</h2>
    <div class="accordion" id="faqAccordion">
        <?php foreach ($faqs as $index => $faq): ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                <button class="accordion-button <?php echo $index !== 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>">
                    <?php echo htmlspecialchars($faq['question']); ?>
                </button>
            </h2>
            <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
