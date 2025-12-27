(function () {
  "use strict";

  // DOMContentLoaded で初期化
  document.addEventListener("DOMContentLoaded", function () {
    init();
  });

  function init() {
    // スムーススクロール（必要に応じて）
    setupSmoothScroll();
    // お問い合わせフォームの初期化
    setupContactForm();
    // FAQアコーディオンの初期化
    setupFaqAccordion();
    // モバイル固定ボタンのスクロールアニメーション
    setupMobileFixedButton();
  }

  function setupSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');

    links.forEach(function (link) {
      link.addEventListener("click", function (e) {
        const href = this.getAttribute("href");

        // 空のハッシュの場合はデフォルト動作
        if (href === "#" || href === "") {
          e.preventDefault();
          return;
        }

        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });
        }
      });
    });
  }

  function setupContactForm() {
    const form = document.getElementById("contactForm");
    if (!form) return;
    
    const textarea = form.querySelector('textarea[name="message"]');
    const charCount = document.getElementById("charCount");
    const errorContainer = document.getElementById("formErrors");
    
    // HTMLエスケープ関数（共通）
    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // 文字数カウンターの更新
    if (textarea && charCount) {
      function updateCharCount() {
        const length = textarea.value.length;
        charCount.textContent = length;
      }

      textarea.addEventListener("input", updateCharCount);
      textarea.addEventListener("paste", function () {
        setTimeout(updateCharCount, 0);
      });

      // 初期値を設定
      updateCharCount();
    }

    // URLパラメータからエラーメッセージを取得して表示
    if (errorContainer) {
      const urlParams = new URLSearchParams(window.location.search);
      const error = urlParams.get("error");
      const errorMessage = urlParams.get("message");

      // デバッグ用（本番環境では削除可能）
      if (error || errorMessage) {
        console.log('Error param:', error);
        console.log('Error message:', errorMessage);
      }

      if (error === "1" && errorMessage) {
        errorContainer.style.display = "block";
        // 複数のエラーメッセージに対応（改行で区切られている場合）
        const messages = decodeURIComponent(errorMessage).split('\n').filter(function(msg) {
          return msg.trim();
        });
        const errorList = messages.map(function(msg) {
          return '<li>' + escapeHtml(msg.trim()) + '</li>';
        }).join('');
        errorContainer.innerHTML = "<ul>" + errorList + "</ul>";
        
        // エラーメッセージの位置までスクロール
        setTimeout(function() {
          errorContainer.scrollIntoView({ behavior: "smooth", block: "nearest" });
        }, 300);
        
        // URLからエラーパラメータを削除（履歴に残さない）
        if (window.history && window.history.replaceState) {
          const newUrl = window.location.pathname + window.location.hash;
          window.history.replaceState({}, '', newUrl);
        }
      }
    }
  }

  function setupFaqAccordion() {
    const faqButtons = document.querySelectorAll('.c-faq-item__button');
    
    faqButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        const answerId = this.getAttribute('aria-controls');
        const answer = document.getElementById(answerId);
        
        // すべてのFAQを閉じる（必要に応じて、複数開けるようにする場合は削除）
        faqButtons.forEach(function(btn) {
          if (btn !== button) {
            btn.setAttribute('aria-expanded', 'false');
            const otherAnswerId = btn.getAttribute('aria-controls');
            const otherAnswer = document.getElementById(otherAnswerId);
            if (otherAnswer) {
              otherAnswer.setAttribute('aria-hidden', 'true');
            }
          }
        });
        
        // クリックされたFAQを開閉
        if (isExpanded) {
          this.setAttribute('aria-expanded', 'false');
          if (answer) {
            answer.setAttribute('aria-hidden', 'true');
          }
        } else {
          this.setAttribute('aria-expanded', 'true');
          if (answer) {
            answer.setAttribute('aria-hidden', 'false');
          }
        }
      });
    });
  }

  function setupMobileFixedButton() {
    const mobileButton = document.querySelector('.p-mobile-fixed-button');
    if (!mobileButton) return;

    const scrollThreshold = 200; // 200pxスクロールしたら表示

    function handleScroll() {
      const scrollY = window.scrollY || window.pageYOffset;
      
      if (scrollY > scrollThreshold) {
        mobileButton.classList.add('is-visible');
      } else {
        mobileButton.classList.remove('is-visible');
      }
    }

    // スクロールイベントを監視（パフォーマンス向上のためthrottle）
    let ticking = false;
    window.addEventListener('scroll', function() {
      if (!ticking) {
        window.requestAnimationFrame(function() {
          handleScroll();
          ticking = false;
        });
        ticking = true;
      }
    });

    // 初期状態をチェック
    handleScroll();
  }
})();
