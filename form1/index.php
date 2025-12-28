<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/csrf.php';

$csrfToken = CSRF::generateToken();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>資料請求用LP ヒアリングシート - Speedix</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/style.css">
</head>
<body>
  <div class="min-h-screen bg-gradient py-8 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg">
      <div class="form-header">
        <div class="flex items-center gap-3 mb-2">
          <svg class="icon-file" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <line x1="10" y1="9" x2="8" y2="9"/>
          </svg>
          <h1 class="text-2xl font-bold">資料請求用LP ヒアリングシート</h1>
        </div>
        <p class="subtitle">ご依頼者様記入用</p>
      </div>

      <div class="section-tabs">
        <button type="button" class="tab-btn active" data-section="1">1</button>
        <button type="button" class="tab-btn" data-section="2">2</button>
        <button type="button" class="tab-btn" data-section="3">3</button>
        <button type="button" class="tab-btn" data-section="4">4</button>
        <button type="button" class="tab-btn" data-section="5">5</button>
        <button type="button" class="tab-btn" data-section="6">6</button>
        <button type="button" class="tab-btn" data-section="7">7</button>
      </div>

      <form
  id="hearingForm"
  method="POST"
  action="<?php echo BASE_URL; ?>/submit.php"
  class="p-8"
  enctype="multipart/form-data"
>


        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="text" name="honeypot" style="display:none;" tabindex="-1" autocomplete="off">

        <!-- セクション1 -->
        <div class="form-section active" data-section="1">
          <h2 class="section-title">1. ご担当者さまについて <span class="required-badge">［必須］</span></h2>

          <div class="form-group">
            <label>1-1. 会社名・屋号 <span class="required">*</span></label>
            <input type="text" name="company_name" required class="form-input">
          </div>

          <div class="form-group">
            <label>1-2. ご担当者名 <span class="required">*</span></label>
            <input type="text" name="contact_person_name" required class="form-input">
          </div>

          <div class="form-group">
            <label>1-3. メールアドレス（ご担当者さま） <span class="required">*</span></label>
            <input type="email" name="contact_email" required class="form-input">
          </div>

          <div class="form-group">
            <label>1-4. お客さま窓口用メールアドレス（資料請求対応用）</label>
            <p class="helper-text">※資料請求フォームからの連絡先として利用します。未記入の場合は、1-3のアドレスを使用します。</p>
            <input type="email" name="customer_support_email" class="form-input">
          </div>

          <div class="form-group">
            <label>1-5. お電話番号 <span class="required">*</span></label>
            <input type="tel" name="phone_number" required class="form-input">
          </div>

          <div class="form-group">
            <label>1-6. 会社情報のURL・ホームページURL（あれば）</label>
            <input type="url" name="company_url" placeholder="https://" class="form-input">
          </div>
        </div>

        <!-- セクション2 -->
        <div class="form-section" data-section="2">
          <h2 class="section-title">2. 資料について <span class="required-badge">［必須］</span></h2>

          <div class="form-group">
            <label>2-1. 資料のタイトル <span class="required">*</span></label>
            <p class="helper-text">例）「○○サービス概要資料」「オンライン○○講座ご案内資料」</p>
            <input type="text" name="document_title" required class="form-input">
          </div>

          <div class="form-group">
            <label class="mb-3">2-2. この資料の主な目的 <span class="required">*</span> <span class="badge-info">［複数選択可］</span></label>
            <div class="checkbox-list">
              <label class="checkbox-label">
                <input type="checkbox" name="document_purposes[]" value="サービスの概要を伝えるため" class="checkbox-input">
                <span>サービスの概要を伝えるため</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="document_purposes[]" value="導入メリット・特徴を伝えるため" class="checkbox-input">
                <span>導入メリット・特徴を伝えるため</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="document_purposes[]" value="導入事例・利用イメージを伝えるため" class="checkbox-input">
                <span>導入事例・利用イメージを伝えるため</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="document_purposes[]" value="社内で共有・検討してもらうため" class="checkbox-input">
                <span>社内で共有・検討してもらうため</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="document_purposes[]" value="比較検討の材料として使ってもらうため" class="checkbox-input">
                <span>比較検討の材料として使ってもらうため</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="document_purposes[]" value="その他" class="checkbox-input" id="purpose-other-check">
                <span>その他</span>
              </label>
              <input type="text" name="document_purpose_other" id="purpose-other-input" placeholder="その他の目的を記入してください（50文字以内）" maxlength="50" class="form-input ml-7" style="display:none;">
            </div>
          </div>
        </div>

        <!-- セクション3 -->
        <div class="form-section" data-section="3">
          <h2 class="section-title">3. 資料を読む相手について <span class="optional-badge">［任意］</span></h2>

          <div class="form-group">
            <label class="mb-3">3-1. 想定している読者区分</label>
            <div class="radio-list">
              <label class="radio-label">
                <input type="radio" name="target_audience_type" value="個人のお客さま向け" class="radio-input">
                <span>個人のお客さま向け</span>
              </label>
              <label class="radio-label">
                <input type="radio" name="target_audience_type" value="企業・団体向け（法人）" class="radio-input">
                <span>企業・団体向け（法人）</span>
              </label>
              <label class="radio-label">
                <input type="radio" name="target_audience_type" value="個人・法人どちらも" class="radio-input">
                <span>個人・法人どちらも</span>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="mb-3">3-2. 主な立場（読者像） <span class="badge-info">［複数選択可］</span></label>
            <div class="checkbox-list">
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_roles[]" value="経営者・事業責任者" class="checkbox-input">
                <span>経営者・事業責任者</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_roles[]" value="部署責任者・マネージャー" class="checkbox-input">
                <span>部署責任者・マネージャー</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_roles[]" value="現場担当者" class="checkbox-input">
                <span>現場担当者</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_roles[]" value="人事・総務・教育担当" class="checkbox-input">
                <span>人事・総務・教育担当</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_roles[]" value="個人の学習者・受講者" class="checkbox-input">
                <span>個人の学習者・受講者</span>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label class="mb-3">3-3. 当てはまりそうな悩み・課題 <span class="badge-info">［複数選択可］</span></label>
            <div class="checkbox-list">
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_concerns[]" value="今のやり方で成果が頭打ちになっている" class="checkbox-input">
                <span>今のやり方で成果が頭打ちになっている</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_concerns[]" value="何から手をつければいいか分からない" class="checkbox-input">
                <span>何から手をつければいいか分からない</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_concerns[]" value="社内の理解や合意形成が進まない" class="checkbox-input">
                <span>社内の理解や合意形成が進まない</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_concerns[]" value="他社・他サービスとの違いが分からない" class="checkbox-input">
                <span>他社・他サービスとの違いが分からない</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_concerns[]" value="人手・時間が足りず、改善に手が回らない" class="checkbox-input">
                <span>人手・時間が足りず、改善に手が回らない</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_concerns[]" value="失敗や無駄な投資を避けたい" class="checkbox-input">
                <span>失敗や無駄な投資を避けたい</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="target_audience_concerns[]" value="比較検討の材料が足りない" class="checkbox-input">
                <span>比較検討の材料が足りない</span>
              </label>
            </div>
          </div>
        </div>

        <!-- セクション4 -->
        <div class="form-section" data-section="4">
          <h2 class="section-title">4. 資料請求後の流れ（ご希望） <span class="optional-badge">［任意］</span></h2>

          <div class="form-group">
            <label class="mb-3">4-1. 資料請求後の基本的な流れ <span class="badge-info">［複数選択可］</span></label>
            <div class="checkbox-list">
              <label class="checkbox-label">
                <input type="checkbox" name="post_request_flow[]" value="資料をメールでお送りするだけ" class="checkbox-input">
                <span>資料をメールでお送りするだけ</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="post_request_flow[]" value="後日、フォローメールでご案内を送りたい" class="checkbox-input">
                <span>後日、フォローメールでご案内を送りたい</span>
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="post_request_flow[]" value="オンライン相談・面談の案内につなげたい" class="checkbox-input">
                <span>オンライン相談・面談の案内につなげたい</span>
              </label>
            </div>
          </div>
        </div>

        <!-- セクション5 -->
        <div class="form-section" data-section="5">
          <h2 class="section-title">5. 資料請求フォームの項目とLPの記載内容の確認 <span class="required-badge">［必須］</span></h2>

          <div class="info-box">
            <h3 class="info-title">5-1. 資料請求フォームの項目について</h3>
            <p class="info-text">実際の資料請求フォームでは、以下の項目を使用します。</p>
            <ul class="info-list">
              <li>お名前</li>
              <li>会社名・屋号</li>
              <li>メールアドレス</li>
              <li>電話番号（任意）</li>
              <li>資料の利用目的・検討の背景（任意・選択式：情報収集のため、社内共有・検討のため、他社サービスとの比較のため、将来の参考のため）</li>
            </ul>
            <label class="checkbox-label-lg">
              <input type="checkbox" name="form_fields_confirmed" value="1" required class="checkbox-input-lg">
              <span class="font-medium">了承しました <span class="required">*</span></span>
            </label>
          </div>

          <div class="info-box">
            <h3 class="info-title">5-2. LP上に記載する注意書きについて</h3>
            <p class="info-text">LP上には、当社側で以下の注意書きを記載いたします。</p>
            <ul class="info-list">
              <li>強い営業・しつこい勧誘は行いません</li>
              <li>個人情報の取り扱いについて簡潔に説明します</li>
              <li>資料だけ受け取っていただいても問題ない旨を明記します</li>
            </ul>
            <label class="checkbox-label-lg">
              <input type="checkbox" name="lp_notes_confirmed" value="1" required class="checkbox-input-lg">
              <span class="font-medium">上記の内容での記載について了承しました <span class="required">*</span></span>
            </label>
          </div>
        </div>

       <!-- セクション6 -->
<div class="form-section" data-section="6">
  <h2 class="section-title">6. 添付資料 <span class="required-badge">［必須］</span></h2>

  <div class="form-group">
    <label>6-1. 資料ファイルの添付 <span class="required">*</span></label>
    <p class="helper-text">
      ※URL添付（Google Drive/Dropbox 等）または、ファイルアップロード（ドラッグ＆ドロップ）どちらかで提出してください。
    </p>

    <!-- ★ポイント：multipart必須なので form タグ側に enctype="multipart/form-data" を付けてください -->
    <div class="file-upload-box" id="dropZone">
      <svg class="icon-file-upload" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
        <polyline points="14 2 14 8 20 8"/>
      </svg>

      <p class="file-upload-text">ここにファイルをドラッグ＆ドロップ</p>
      <p class="file-upload-sub">または</p>

      <button type="button" class="btn-file" id="filePickBtn">ファイルを選択</button>
      <input
        type="file"
        name="document_file"
        id="documentFile"
        class="file-input-hidden"
        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.png,.jpg,.jpeg"
      >

      <div class="file-selected" id="fileSelected" style="display:none;">
        <span class="file-selected-label">選択中：</span>
        <span id="fileSelectedName"></span>
        <button type="button" class="btn-file-clear" id="fileClearBtn">解除</button>
      </div>

      <div class="file-or-line">または URL で提出</div>

      <input
        type="url"
        name="document_file_url"
        id="documentFileUrl"
        placeholder="ファイル共有URLを入力（例：Google Drive、Dropboxなど）"
        class="form-input file-url-input"
      >
      <p class="file-upload-note">どちらか一方が必須です（URL入力またはファイルアップロード）</p>
    </div>

    <div class="field-error" id="section6Error" style="display:none;"></div>
  </div>
</div>

<!-- セクション6専用JS（index.phpの末尾でOK。script.jsに統合しても可） -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const dropZone = document.getElementById('dropZone');
  const filePickBtn = document.getElementById('filePickBtn');
  const fileInput = document.getElementById('documentFile');
  const urlInput = document.getElementById('documentFileUrl');

  const fileSelected = document.getElementById('fileSelected');
  const fileSelectedName = document.getElementById('fileSelectedName');
  const fileClearBtn = document.getElementById('fileClearBtn');

  const err = document.getElementById('section6Error');

  if (!dropZone || !fileInput || !urlInput) return;

  const showError = (msg) => {
    if (!err) return;
    err.textContent = msg;
    err.style.display = 'block';
  };
  const hideError = () => {
    if (!err) return;
    err.textContent = '';
    err.style.display = 'none';
  };

  const updateUI = () => {
    const hasFile = fileInput.files && fileInput.files.length > 0;
    if (hasFile) {
      fileSelectedName.textContent = fileInput.files[0].name;
      fileSelected.style.display = 'flex';
      // ファイルが選ばれたらURLは任意扱いにしておく（入力は残してもOKだが混乱防止でクリア）
      urlInput.value = '';
    } else {
      fileSelected.style.display = 'none';
      fileSelectedName.textContent = '';
    }
  };

  // ファイル選択
  filePickBtn?.addEventListener('click', () => fileInput.click());
  fileInput.addEventListener('change', () => {
    hideError();
    updateUI();
  });

  // 解除
  fileClearBtn?.addEventListener('click', () => {
    fileInput.value = '';
    updateUI();
  });

  // URL入力したらファイルは解除（どちらか一方の前提）
  urlInput.addEventListener('input', () => {
    hideError();
    if (urlInput.value.trim() !== '') {
      fileInput.value = '';
      updateUI();
    }
  });

  // D&D
  const prevent = (e) => { e.preventDefault(); e.stopPropagation(); };

  ['dragenter','dragover','dragleave','drop'].forEach(evt => {
    dropZone.addEventListener(evt, prevent);
  });

  dropZone.addEventListener('dragover', () => dropZone.classList.add('is-dragover'));
  dropZone.addEventListener('dragleave', () => dropZone.classList.remove('is-dragover'));
  dropZone.addEventListener('drop', (e) => {
    dropZone.classList.remove('is-dragover');
    hideError();

    const files = e.dataTransfer.files;
    if (!files || files.length === 0) return;

    // 1ファイル運用
    if (files.length > 1) {
      showError('アップロードは1ファイルのみ対応しています。');
      return;
    }

    // 入力にセット
    fileInput.files = files;
    updateUI();
  });

  // 「どちらか必須」チェック（submit前に最低限）
  const form = document.getElementById('hearingForm');
  form?.addEventListener('submit', (e) => {
    const hasFile = fileInput.files && fileInput.files.length > 0;
    const hasUrl = urlInput.value.trim() !== '';
    if (!hasFile && !hasUrl) {
      e.preventDefault();
      showError('URL入力またはファイルアップロードのどちらかを提出してください。');
      // セクション6を表示している前提のUIならここでスクロールだけ
      dropZone.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });

  updateUI();
});
</script>


        <!-- セクション7 -->
        <div class="form-section" data-section="7">
          <h2 class="section-title">7. ご希望・その他ご要望について <span class="optional-badge">［任意］</span></h2>

          <div class="form-group">
            <label>7-1. 資料請求用LPや資料請求フォームについて、特にご希望や気になる点があればご記入ください</label>
            <p class="helper-text">質問はよくある質問のページをご確認ください</p>
            <p class="helper-text">例）LPで特に強調してほしい点 / 使ってほしい・避けてほしい表現 / 参考にしてほしい資料請求ページやサイトのURL</p>
            <textarea name="additional_requests" rows="6" class="form-textarea" placeholder="ご要望をご記入ください..."></textarea>
          </div>
        </div>

        <!-- ナビ（必ず form 直下の最後に置く） -->
        <div class="form-nav">
          <button type="button" class="btn-prev" id="prevBtn" disabled>前へ</button>
          <span class="section-indicator">セクション <span id="currentSection">1</span> / 7</span>
          <button type="button" class="btn-next" id="nextBtn">次へ</button>
          <button type="submit" class="btn-submit" id="submitBtn" style="display:none;">この内容で送信する</button>
        </div>
      </form>
    </div>
  </div>

  <script src="<?php echo BASE_URL; ?>/assets/script.js"></script>
</body>
</html>
