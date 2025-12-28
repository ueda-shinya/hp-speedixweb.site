document.addEventListener('DOMContentLoaded', function() {
    let currentSection = 1;
    const totalSections = 7;
    
    const form = document.getElementById('hearingForm');
    const sections = document.querySelectorAll('.form-section');
    const tabs = document.querySelectorAll('.tab-btn');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const currentSectionSpan = document.getElementById('currentSection');

// ===== Section 6: URL or File Upload (either required) =====
const dropZone = document.getElementById('dropZone');
const filePickBtn = document.getElementById('filePickBtn');
const fileInput = document.getElementById('documentFile');
const fileSelected = document.getElementById('fileSelected');
const fileSelectedName = document.getElementById('fileSelectedName');
const fileClearBtn = document.getElementById('fileClearBtn');
const documentFileUrl = document.getElementById('documentFileUrl');
const section6Error = document.getElementById('section6Error');

function showSection6Error(msg) {
  if (!section6Error) return;
  section6Error.textContent = msg;
  section6Error.style.display = 'block';
}
function clearSection6Error() {
  if (!section6Error) return;
  section6Error.textContent = '';
  section6Error.style.display = 'none';
}

function updateFileUI() {
  const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
  if (hasFile) {
    fileSelectedName.textContent = fileInput.files[0].name;
    fileSelected.style.display = 'flex';
    // ファイルがある場合、URL入力は任意なので required を外す
    if (documentFileUrl) documentFileUrl.required = false;
  } else {
    fileSelected.style.display = 'none';
  }
}

function clearFile() {
  if (!fileInput) return;
  fileInput.value = '';
  updateFileUI();
}

function validateSection6() {
  clearSection6Error();

  const hasFile = fileInput && fileInput.files && fileInput.files.length > 0;
  const hasUrl = documentFileUrl && documentFileUrl.value.trim().length > 0;

  if (!hasFile && !hasUrl) {
    showSection6Error('資料は「URL入力」または「ファイルアップロード」のどちらかで提出してください。');
    return false;
  }
  return true;
}

if (filePickBtn && fileInput) {
  filePickBtn.addEventListener('click', () => fileInput.click());
}

if (fileInput) {
  fileInput.addEventListener('change', () => {
    updateFileUI();
    // ファイル選択したらURLは空にする（任意）
    if (fileInput.files.length > 0 && documentFileUrl) documentFileUrl.value = '';
    clearSection6Error();
  });
}

if (fileClearBtn) {
  fileClearBtn.addEventListener('click', () => {
    clearFile();
    clearSection6Error();
  });
}

if (documentFileUrl) {
  documentFileUrl.addEventListener('input', () => {
    // URLが入力されたらファイルは解除（任意）
    const hasUrl = documentFileUrl.value.trim().length > 0;
    if (hasUrl) clearFile();
    clearSection6Error();
  });
}

if (dropZone && fileInput) {
  ['dragenter', 'dragover'].forEach(evt => {
    dropZone.addEventListener(evt, (e) => {
      e.preventDefault();
      e.stopPropagation();
      dropZone.classList.add('dragover');
    });
  });

  ['dragleave', 'drop'].forEach(evt => {
    dropZone.addEventListener(evt, (e) => {
      e.preventDefault();
      e.stopPropagation();
      dropZone.classList.remove('dragover');
    });
  });

  dropZone.addEventListener('drop', (e) => {
    const files = e.dataTransfer.files;
    if (files && files.length > 0) {
      fileInput.files = files; // ブラウザによっては動かない場合あり（その場合は選択ボタンで対応）
      updateFileUI();
      if (documentFileUrl) documentFileUrl.value = '';
      clearSection6Error();
    }
  });
}

// 「次へ」でセクション6にいるときだけブロック
if (nextBtn) {
  nextBtn.addEventListener('click', function() {
    if (currentSection === 6) {
      if (!validateSection6()) return;
    }
  });
}

// 送信時も最終チェック（必須）
if (form) {
  form.addEventListener('submit', function(e) {
    if (!validateSection6()) {
      e.preventDefault();
      showSection(6);
      return;
    }
  });
}

    
    // その他チェックボックスの処理
    const purposeOtherCheck = document.getElementById('purpose-other-check');
    const purposeOtherInput = document.getElementById('purpose-other-input');
    
    if (purposeOtherCheck && purposeOtherInput) {
        purposeOtherCheck.addEventListener('change', function() {
            purposeOtherInput.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // セクション表示切り替え
    function showSection(sectionNum) {
        currentSection = sectionNum;
        
        sections.forEach(section => {
            section.classList.remove('active');
            if (parseInt(section.dataset.section) === sectionNum) {
                section.classList.add('active');
            }
        });
        
        tabs.forEach(tab => {
            tab.classList.remove('active');
            if (parseInt(tab.dataset.section) === sectionNum) {
                tab.classList.add('active');
            }
        });
        
        currentSectionSpan.textContent = sectionNum;
        
        prevBtn.disabled = sectionNum === 1;
        
        if (sectionNum === totalSections) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'inline-block';
        } else {
            nextBtn.style.display = 'inline-block';
            submitBtn.style.display = 'none';
        }
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // タブクリック
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const sectionNum = parseInt(this.dataset.section);
            showSection(sectionNum);
        });
    });
    
    // 前へボタン
    prevBtn.addEventListener('click', function() {
        if (currentSection > 1) {
            showSection(currentSection - 1);
        }
    });
    
    // 次へボタン
   nextBtn.addEventListener('click', function () {
    const currentSectionEl = document.querySelector(
        `.form-section[data-section="${currentSection}"]`
    );

    // このセクション内の必須項目を取得
    const requiredFields = currentSectionEl.querySelectorAll('[data-required="1"], [required]');

    for (let field of requiredFields) {
        if (field.type === 'checkbox' && !field.checked) {
            alert('必須項目を入力してください。');
            field.focus();
            return;
        }

        if (field.value !== undefined && field.value.trim() === '') {
            alert('必須項目を入力してください。');
            field.focus();
            return;
        }
    }

    // 問題なければ次へ
    if (currentSection < totalSections) {
        showSection(currentSection + 1);
    }
});

    
    // フォーム送信
    form.addEventListener('submit', function(e) {
        // セクション1の必須チェック
        const companyName = form.querySelector('[name="company_name"]').value.trim();
        const contactPersonName = form.querySelector('[name="contact_person_name"]').value.trim();
        const contactEmail = form.querySelector('[name="contact_email"]').value.trim();
        const phoneNumber = form.querySelector('[name="phone_number"]').value.trim();
        
        if (!companyName || !contactPersonName || !contactEmail || !phoneNumber) {
            e.preventDefault();
            alert('セクション1の必須項目をすべて入力してください。');
            showSection(1);
            return;
        }
        
        // セクション2の必須チェック
        const documentTitle = form.querySelector('[name="document_title"]').value.trim();
        const documentPurposes = form.querySelectorAll('[name="document_purposes[]"]:checked');
        
        if (!documentTitle || documentPurposes.length === 0) {
            e.preventDefault();
            alert('セクション2の必須項目をすべて入力してください。');
            showSection(2);
            return;
        }
        
        // セクション5の必須チェック
        const formFieldsConfirmed = form.querySelector('[name="form_fields_confirmed"]').checked;
        const lpNotesConfirmed = form.querySelector('[name="lp_notes_confirmed"]').checked;
        
        if (!formFieldsConfirmed || !lpNotesConfirmed) {
            e.preventDefault();
            alert('セクション5の確認項目にすべてチェックを入れてください。');
            showSection(5);
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.textContent = '送信中...';
    });
    
    // 初期表示
    showSection(1);
});
