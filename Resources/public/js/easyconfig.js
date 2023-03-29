document.addEventListener('DOMContentLoaded', function () {
    // Making fields readonly those contain global value
    (function () {
        const checkBoxes = document.querySelectorAll(".derived-from-global");
        for (var i = 0; i < checkBoxes.length; i++) {
            const $checkBoxEl = checkBoxes[i];

            if ($checkBoxEl.checked) {
                var $inputField = $checkBoxEl.id.replace('Preference', '');
                document.getElementById($inputField).readOnly = true;
            }
        }
    })();

    var form = document.getElementById('config-form-0');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", form.action, true);
        // xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                location.reload();
            }
        }

        xhr.onerror = function () {
            console.log("Error:", xhr.statusText);
        };

        xhr.send(formData);
    });

    function getValueBySpecificKey(url, params, inputEl)
    {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url + '?' + new URLSearchParams(params));
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    inputEl.value = xhr.responseText.replace(/^"|"$/g, '');
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };

        xhr.send();
    }

    document.querySelectorAll('.derived-from-global').forEach(function (e) {
        e.addEventListener('change', function () {
            var $inputID = this.getAttribute('id').replace('Preference', '');
            var $fieldName =  this.getAttribute('name').replace('Preference', '');
            var $fieldKey = $fieldName.substring($fieldName.indexOf('[') + 1, $fieldName.indexOf(']'));
            var $inputEl = document.getElementById($inputID);
            var isGlobal;

            if (this.checked) {
                $inputEl.readOnly = true;
                isGlobal = 1;
            } else {
                $inputEl.readOnly = false;
                isGlobal = 0;
            }

            var $url = document.querySelector('.value-retrieving-url').dataset.url;
            var $groupKey = document.querySelector('.group-key').dataset.groupkey;
            var $params = {
                'is_global': isGlobal,
                'key': $groupKey + '.' + $fieldKey
            };

            getValueBySpecificKey($url, $params, $inputEl);
        });
    });
});
