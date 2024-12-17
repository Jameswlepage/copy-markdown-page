document.addEventListener('DOMContentLoaded', function () {
    var button = document.querySelector('.cmp-copy-btn');
    if (!button) return;

    // Try to find the content container
    var contentEl = document.querySelector('.entry-content, .post-content, article, .hentry, .type-page, .type-post');
    if (!contentEl) {
        // fallback to body if nothing found
        contentEl = document.body;
    }

    // We expect Turndown is already loaded
    var turndownService = new TurndownService();

    button.addEventListener('click', function () {
        var htmlContent = contentEl.innerHTML;
        var markdown = turndownService.turndown(htmlContent);

        navigator.clipboard.writeText(markdown).then(function () {
            button.classList.add('copied');
            setTimeout(function () {
                button.classList.remove('copied');
            }, 2000);
        }).catch(function (err) {
            console.error('Error copying text: ', err);
        });
    });
});
