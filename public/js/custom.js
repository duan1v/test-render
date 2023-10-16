function copyToClipboard(textToCopy) {
    // navigator clipboard 需要https等安全上下文
    if (navigator.clipboard && window.isSecureContext) {
        // navigator clipboard 向剪贴板写文本
        return navigator.clipboard.writeText(textToCopy);
    } else {
        // 创建text area
        let textArea = document.createElement("textarea");
        textArea.value = textToCopy;
        // 使text area不在viewport，同时设置不可见
        textArea.style.position = "absolute";
        textArea.style.opacity = 0;
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        return new Promise((res, rej) => {
            // 执行复制命令并移除文本框
            document.execCommand('copy') ? res() : rej();
            textArea.remove();
        });
    }
}

function setCookie(cookieName, cookieValue, expirationDays) {
    var date = new Date();
    date.setTime(date.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + date.toUTCString();
    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
}

function getCookie(name) {
    var cookies = document.cookie.split("; ");
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].split("=");
        if (cookie[0] === name) {
            return cookie[1];
        }
    }
    return null;
}

function loadPdf(url, workerSrc, cid) {
    var pdfJsLib = window['pdfjs-dist/build/pdf'];

    pdfJsLib.GlobalWorkerOptions.workerSrc = workerSrc;

    // Asynchronous download of PDF
    var loadingTask = pdfJsLib.getDocument(url);
    loadingTask.promise.then(function (pdf) {
        var pagesCount = pdf.numPages;
        var container = document.getElementById(cid);
        container.innerHTML = '';
        for (var i = 1; i <= pagesCount; i++) {
            pdf.getPage(i).then(function (page) {
                var desiredWidth = container.offsetWidth;
                // console.log(desiredWidth)
                var viewport = page.getViewport({scale: 1,});
                var scale = desiredWidth / viewport.width;
                viewport = page.getViewport({scale: scale,});

                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };

                page.render(renderContext);
                var pageContainer = document.createElement('div');
                pageContainer.className = 'pdfPage';
                pageContainer.appendChild(canvas);
                container.appendChild(pageContainer);
            });
        }
    }, function (reason) {
        console.error(reason);
    });
}

