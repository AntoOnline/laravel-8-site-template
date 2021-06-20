byteArray = new Uint8Array([181, 143, 16, 173, 231, 56, 63, 149, 181, 185, 224, 124, 84, 230, 123, 36]);
var dec = $('#decode'), enc = $('#encode'), res = $('#result'), clr = $('#clear'), sl = $('#splitLines'), ls = $('#lineSplit'), fi = $('#fileInput'), fl = $('#fileLoad'), tf = $('#typeOfFile');
var fileExt = "", fileTyp = "", binary = null;

function handleFileSelect() {
    if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
        alert('The File APIs are not fully supported in this browser.');
        return;
    }

    try {
        var file = document.getElementById('fileInput').files[0];
        if (file) {
            fileTyp = file.type;
            fileExt = file.name.substr(file.name.lastIndexOf('.') + 1);

            var reader = new FileReader();
            reader.onload = function (e) {
                if (tf.val() === 'binary') {
                    res.val('-- Binary File Loaded--');
                    binary = window.btoa(e.target.result);
                } else {
                    res.val(e.target.result);
                }
            };

            if (tf.val() === 'binary') {
                reader.readAsBinaryString(file);
            } else {
                reader.readAsText(file);
            }
        }
    } catch (err) {
        alert("Could not upload the file.");
    }
}

function b64DecodeUnicode(str) {
    try {
        return decodeURIComponent(atob(str).split('').map(function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
    } catch (err) {
        alert("Could not decode string unicode.");
    }
}

function base64EncodeUnicode(str) {
    utf8Bytes = encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function (match, p1) {
        return String.fromCharCode('0x' + p1);
    });

    return window.btoa(utf8Bytes);
}

function disabled(state) {
    res.prop('disabled', state);
    enc.prop('disabled', state);
    dec.prop('disabled', state);
    clr.prop('disabled', state);
    if (tf.val() !== 'binary') {
        sl.prop('disabled', state);
        ls.prop('disabled', state);
    }
}

function addNewlines(str) {
    var maxChars = 76;
    var result = '';

    while (str.length > 0) {
        if (ls.val() === '1inux') {
            result += str.substring(0, maxChars) + '\n';
        }
        if (ls.val() === 'windows') {
            result += str.substring(0, maxChars) + '\n';
        }
        str = str.substring(maxChars);
    }

    return result;
}

tf.change(function () {
    if (tf.val() === 'binary') {
        sl.prop('disabled', true);
        ls.prop('disabled', true);
    } else {
        sl.prop('disabled', false);
        ls.prop('disabled', false);
    }
});

fl.click(function () {
    handleFileSelect();
});

enc.click(function () {
    disabled(true);

    try {
        if (tf.val() === 'binary') {
            if (binary === null) {
                alert("First select a file using 'choose file' and then click 'load'.");
            } else {
                res.val(binary);
            }
        } else {
            if (res.val() === "") {
                alert("First select a file using 'choose file' and then click 'load'.");
            } else {
                newValue = base64EncodeUnicode(res.val());

                if (sl.prop('checked') === true) {
                    newValue = addNewlines(newValue);
                }

                res.val(newValue);
            }
        }
    } catch (err) {
        alert("The string to be encoded could not be encoded. ");
    }

    disabled(false);
});

dec.click(function () {
    disabled(true);

    try {
        if (tf.val() === 'binary') {
            if (binary === null) {
                alert("First add base 64 to decode.");
            } else {
                /*
                 var bytes = base64ToArrayBuffer(res.val());
                 var fileType = detectMimeType(bytes);
                 */
                saveByteArray(base64ToArrayBuffer(res.val()));
            }
        } else {
            if (res.val() === "") {
                alert("First add base 64 to decode.");
            } else {
                res.val(b64DecodeUnicode(res.val()));
                /*res.val(window.atob(res.val()));*/
            }
        }
    } catch (err) {
        alert("The string to be decoded is not correctly encoded.");
    }

    disabled(false);
});

clr.click(function () {
    disabled(true);

    res.val('');

    disabled(false);
});

function toHexString(byteArray) {
    return Array.prototype.map.call(byteArray, function (byte) {
        return ('0' + (byte & 0xFF).toString(16)).slice(-2);
    }).join('');
}

function toByteArray(hexString) {
    var result = [];
    for (var i = 0; i < hexString.length; i += 2) {
        result.push(parseInt(hexString.substr(i, 2), 16));
    }
    return result;
}

function base64ToArrayBuffer(base64) {
    try {
        var binaryString = window.atob(base64);
        var binaryLen = binaryString.length;
        var bytes = new Uint8Array(binaryLen);
        for (var i = 0; i < binaryLen; i++) {
            var ascii = binaryString.charCodeAt(i);
            bytes[i] = ascii;
        }

        return bytes;
    } catch (err) {
        alert("Could not decode the string to a buffer.");
    }
}

function saveByteArray(byte) {
    $.confirm({
        title: 'Choose the file type',
        content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Please choose the expected file type</label>' +
                '<select class="fileType form-control" required>' +
                '<option value="audio/aac">.aac</option>' +
                '<option value="application/x-abiword">.abw</option>' +
                '<option value="application/x-freearc">.arc</option>' +
                '<option value="video/x-msvideo">.avi</option>' +
                '<option value="application/vnd.amazon.ebook">.azw</option>' +
                '<option value="application/octet-stream">.bin</option>' +
                '<option value="image/bmp">.bmp</option>' +
                '<option value="application/x-bzip">.bz</option>' +
                '<option value="application/x-bzip2">.bz2</option>' +
                '<option value="application/x-csh">.csh</option>' +
                '<option value="text/css">.css</option>' +
                '<option value="text/csv">.csv</option>' +
                '<option value="application/msword">.doc</option>' +
                '<option value="application/vnd.openxmlformats-officedocument.wordprocessingml.document">.docx</option>' +
                '<option value="application/vnd.ms-fontobject">.eot</option>' +
                '<option value="application/epub+zip">.epub</option>' +
                '<option value="application/gzip">.gz</option>' +
                '<option value="image/gif">.gif</option>' +
                '<option value="text/html">.htm</option>' +
                '<option value="image/vnd.microsoft.icon">.ico</option>' +
                '<option value="text/calendar">.ics</option>' +
                '<option value="application/java-archive">.jar</option>' +
                '<option value="image/jpeg">.jpg</option>' +
                '<option value="text/javascript">.js</option>' +
                '<option value="application/json">.json</option>' +
                '<option value="application/ld+json">.jsonld</option>' +
                '<option value="audio/midi audio/x-midi">.mid</option>' +
                '<option value="text/javascript">.mjs</option>' +
                '<option value="audio/mpeg">.mp3</option>' +
                '<option value="video/mpeg">.mpeg</option>' +
                '<option value="application/vnd.apple.installer+xml">.mpkg</option>' +
                '<option value="application/vnd.oasis.opendocument.presentation">.odp</option>' +
                '<option value="application/vnd.oasis.opendocument.spreadsheet">.ods</option>' +
                '<option value="application/vnd.oasis.opendocument.text">.odt</option>' +
                '<option value="audio/ogg">.oga</option>' +
                '<option value="video/ogg">.ogv</option>' +
                '<option value="application/ogg">.ogx</option>' +
                '<option value="audio/opus">.opus</option>' +
                '<option value="font/otf">.otf</option>' +
                '<option value="image/png">.png</option>' +
                '<option value="application/pdf">.pdf</option>' +
                '<option value="application/php">.php</option>' +
                '<option value="application/vnd.ms-powerpoint">.ppt</option>' +
                '<option value="application/vnd.openxmlformats-officedocument.presentationml.presentation">.pptx</option>' +
                '<option value="application/vnd.rar">.rar</option>' +
                '<option value="application/rtf">.rtf</option>' +
                '<option value="application/x-sh">.sh</option>' +
                '<option value="image/svg+xml">.svg</option>' +
                '<option value="application/x-shockwave-flash">.swf</option>' +
                '<option value="application/x-tar">.tar</option>' +
                '<option value="image/tiff">.tif</option>' +
                '<option value="video/mp2t">.ts</option>' +
                '<option value="font/ttf">.ttf</option>' +
                '<option value="text/plain">.txt</option>' +
                '<option value="application/vnd.visio">.vsd</option>' +
                '<option value="audio/wav">.wav</option>' +
                '<option value="audio/webm">.weba</option>' +
                '<option value="video/webm">.webm</option>' +
                '<option value="image/webp">.webp</option>' +
                '<option value="font/woff">.woff</option>' +
                '<option value="font/woff2">.woff2</option>' +
                '<option value="application/xhtml+xml">.xhtml</option>' +
                '<option value="application/vnd.ms-excel">.xls</option>' +
                '<option value="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">.xlsx</option>' +
                '<option value="application/xml">.xml (application)</option>' +
                '<option value="text/xml">.xml (text)</option>' +
                '<option value="application/vnd.mozilla.xul+xml">.xul</option>' +
                '<option value="application/zip">.zip</option>' +
                '<option value="video/3gpp">.3gp (video/audio)</option>' +
                '<option value="audio/3gpp">.3gp (audio)</option>' +
                '<option value="video/3gpp2">.3g2 (video/audio)</option>' +
                '<option value="audio/3gpp2">.3g2 (audio)</option>' +
                '<option value="application/x-7z-compressed">.7z</option>' +
                '</select>' +
                '</div>' +
                '</form>',
        buttons: {
            formSubmit: {
                text: 'Submit',
                btnClass: 'btn-blue',
                action: function () {
                    var fileType = this.$content.find('.fileType').val();
                    if (!fileType) {
                        $.alert('Please select a file type.');
                        return false;
                    }
                    var blob = new Blob([byte], {type: fileType});

                    var link = document.getElementById('link');
                    link.href = window.URL.createObjectURL(blob);
                    link.innerHTML = "Click here to download the file using mime: " + fileType;
                    link.download = "b64DecodedFile";
                }
            },
            cancel: function () {
                /* do nothing */
            },
        },
        onContentReady: function () {
            /* do nothing */
        }
    });
}

/* Not used - 
 * It investigates bytes signature to determine mime.
 * Confuses zip with pptx etc, much more work needed.
 */
function detectMimeType(bytes) {
    var arr = (new Uint8Array(bytes)).subarray(0, 40);

    mimeSignature = toHexString(arr).toString().toUpperCase();

    console.log('mimeSignature: ' + mimeSignature);

    mime = "";

    /* PNG */
    if (mimeSignature.search('89504E470D0A1A0A') === 0) {
        mime = "image/png";
    }

    /* GIF */
    if (mimeSignature.search('47494638') === 0) {
        mime = "image/gif";
    }

    /* AVI */
    if (mimeSignature.search('52494646') === 0) {
        mime = "video/avi";
    }

    /* JPG / JFIF*/
    if (mimeSignature.search('FFD8FFE0') === 0) {
        mime = "image/jpeg";
    }
    if (mimeSignature.search('FFD8FFE1') === 0) {
        mime = "image/jpeg";
    }
    if (mimeSignature.search('FFD8FFE2') === 0) {
        mime = "image/jpeg";
    }
    if (mimeSignature.search('FFD8FFE3') === 0) {
        mime = "image/jpeg";
    }

    /* ZIP */
    if (mimeSignature.search('504B0304') === 0) {
        mime = "application/zip";
    }

    /* BMP */
    if (mimeSignature.search('424D') === 0) {
        mime = "image/bmp";
    }

    /* ICO */
    if (mimeSignature.search('00000100') === 0) {
        mime = "image/x-icon";
    }

    /* TIFF */
    if (mimeSignature.search('49492A00') === 0) {
        mime = "image/tiff";
    }
    if (mimeSignature.search('4D4D002A') === 0) {
        mime = "image/tiff";
    }

    console.log('mime: ' + mime);

    return mime;
}