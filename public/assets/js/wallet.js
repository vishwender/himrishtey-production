/* =========================================
   wallet.js — HimRishtey
   Wallet Page Logic
   ========================================= */

(function () {
  'use strict';

  // ---- Mock data (replace with real API calls) ----
  // const MOCK_DATA = {
  //   wallet_balance: '1,250',
  //   wallet_transactions: [
  //     { amount_added: '500',  amount_deducted: '0',   created_at: '23 Jun 2026' },
  //     { amount_added: '0',    amount_deducted: '200', created_at: '22 Jun 2026' },
  //     { amount_added: '1000', amount_deducted: '0',   created_at: '20 Jun 2026' },
  //     { amount_added: '0',    amount_deducted: '50',  created_at: '19 Jun 2026' },
  //     { amount_added: '0',    amount_deducted: '100', created_at: '18 Jun 2026' },
  //     { amount_added: '250',  amount_deducted: '0',   created_at: '15 Jun 2026' },
  //   ],
  //   wallet_offers: [
  //     { title: 'Starter Pack',  description: 'Get 10% bonus coins on your first recharge. Limited offer for new users.', amount: '199' },
  //     { title: 'Power Pack',    description: 'Our most popular plan. Unlock unlimited interest sends for 30 days.', amount: '499' },
  //     { title: 'Premium Pack',  description: 'Full access to all features including direct chat for 90 days.', amount: '999' },
  //     { title: 'Elite Pack',    description: 'Annual plan with maximum benefits and dedicated matchmaking support.', amount: '1999' },
  //   ]
  // };

  const MOCK_DATA = window.walletData || {
    wallet_balance: 0,
    wallet_transactions: [],
    wallet_offers: []
};

  // ---- DOM refs ----
  let walletData = {};

  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    loadWalletData();
    initModal();
    initQuickChips();
  });

  // =============================================
  // LOAD DATA (simulated fetch — replace with real API)
  // =============================================
  function loadWalletData() {
    const skeleton = document.getElementById('wltSkeleton');
    const content  = document.getElementById('wltContent');

    // Simulate network delay
    setTimeout(() => {
      walletData = MOCK_DATA;

      renderBalance(walletData.wallet_balance);
      renderTransactions(walletData.wallet_transactions);
      renderOffers(walletData.wallet_offers);

      if (skeleton) skeleton.setAttribute('hidden', '');
      if (content)  content.removeAttribute('hidden');

      if (typeof lucide !== 'undefined') lucide.createIcons();
    }, );
  }

  // =============================================
  // RENDER BALANCE
  // =============================================
  function renderBalance(balance) {
    const balEl      = document.getElementById('wltBalanceValue');
    const navbarEl   = document.getElementById('navbarBalance');
    if (balEl)    balEl.textContent = balance;
    if (navbarEl) navbarEl.textContent = '₹' + balance;
  }

  // =============================================
  // RENDER TRANSACTIONS
  // =============================================
  function renderTransactions(txns) {
    const list     = document.getElementById('wltTxnList');
    const emptyEl  = document.getElementById('wltTxnEmpty');
    if (!list) return;

    list.innerHTML = '';

    // Reverse so newest first (mirrors Flutter logic)
    //const reversed = [...txns].reverse();
    const reversed = txns;

    if (reversed.length === 0) {
      if (emptyEl) emptyEl.removeAttribute('hidden');
      return;
    }

    reversed.forEach(txn => {
      const isCredit = txn.amount_added !== '0' && txn.amount_added !== '';
      const amount   = isCredit ? txn.amount_added : txn.amount_deducted;
      const sign     = isCredit ? '+' : '-';
      const typeText = isCredit ? 'Credit' : 'Debit';
      const date     = txn.created_at || '';

      const li = document.createElement('li');
      li.className = 'wlt-txn-item';
      li.setAttribute('role', 'listitem');
      li.innerHTML = `
        <div class="wlt-txn-left">
          <span class="wlt-txn-icon ${isCredit ? 'credit' : 'debit'}" aria-hidden="true">
            <i data-lucide="${isCredit ? 'arrow-down-left' : 'arrow-up-right'}" width="16" height="16"></i>
          </span>
          <div>
            <div class="wlt-txn-type">${typeText}</div>
            ${date ? `<div class="wlt-txn-date">${date}</div>` : ''}
          </div>
        </div>
        <span class="wlt-txn-amount ${isCredit ? 'credit' : 'debit'}" aria-label="${typeText} ₹${amount}">
          ${sign}₹${amount}
        </span>
      `;
      list.appendChild(li);
    });
  }

  // =============================================
  // RENDER OFFERS
  // =============================================
  function renderOffers(offers) {
    const list    = document.getElementById('wltOfferList');
    const emptyEl = document.getElementById('wltOffersEmpty');
    if (!list) return;

    list.innerHTML = '';

    if (offers.length === 0) {
      if (emptyEl) emptyEl.removeAttribute('hidden');
      return;
    }

    offers.forEach(offer => {
      const li = document.createElement('li');
      li.className = 'wlt-offer-item';
      li.innerHTML = `
        <div class="wlt-offer-info">
          <p class="wlt-offer-title">${offer.title}</p>
          <p class="wlt-offer-desc">${offer.description}</p>
        </div>
        <div class="wlt-offer-right">
          <span class="wlt-offer-price">₹${offer.amount}</span>
          <button class="wlt-buy-btn" data-offer='${JSON.stringify(offer)}' aria-label="Buy ${offer.title} for ₹${offer.amount}">
            <i data-lucide="zap" width="14" height="14"></i>
            Buy Now
          </button>
        </div>
      `;
      list.appendChild(li);

      li.querySelector('.wlt-buy-btn').addEventListener('click', e => {
        const offerData = JSON.parse(e.currentTarget.dataset.offer);
        handleBuyOffer(offerData);
      });
    });
  }

  // =============================================
  // ADD MONEY MODAL
  // =============================================
  function initModal() {
    const overlay    = document.getElementById('wltModalOverlay');
    const addBtn     = document.getElementById('wltAddMoneyBtn');
    const closeBtn   = document.getElementById('wltModalClose');
    const cancelBtn  = document.getElementById('wltModalCancel');
    const proceedBtn = document.getElementById('wltModalAdd');
    const errorEl    = document.getElementById('wltAmountError');
    const input      = document.getElementById('wltAmountInput');

    if (!overlay) return;

    function openModal() {
      overlay.removeAttribute('hidden');
      if (input) { input.value = ''; input.focus(); }
      if (errorEl) errorEl.setAttribute('hidden', '');
      clearActiveChips();
      if (typeof lucide !== 'undefined') lucide.createIcons();
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      overlay.setAttribute('hidden', '');
      document.body.style.overflow = '';
    }

    addBtn?.addEventListener('click', openModal);
    closeBtn?.addEventListener('click', closeModal);
    cancelBtn?.addEventListener('click', closeModal);

    // Close on overlay backdrop click
    overlay.addEventListener('click', e => {
      if (e.target === overlay) closeModal();
    });

    // Close on Escape key
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && !overlay.hidden) closeModal();
    });

    // Proceed to pay
    proceedBtn?.addEventListener('click', () => {
        console.log('i am here');
      const amount = parseInt(input?.value, 10);
      if (!amount || amount < 1 || amount > 10000) {
        if (errorEl) errorEl.removeAttribute('hidden');
        input?.focus();
        return;
      }
      if (errorEl) errorEl.setAttribute('hidden', '');

      // Simulate payment flow — replace with Razorpay integration
      handleAddMoney({ amount: amount.toString(), title: 'Add Money' });
      closeModal();
    });

    // Clear error on typing
    input?.addEventListener('input', () => {
      if (errorEl) errorEl.setAttribute('hidden', '');
    });
  }

  // =============================================
  // QUICK AMOUNT CHIPS
  // =============================================
  function initQuickChips() {
    const chips = document.querySelectorAll('.wlt-chip[data-amount]');
    const input = document.getElementById('wltAmountInput');

    chips.forEach(chip => {
      chip.addEventListener('click', () => {
        clearActiveChips();
        chip.classList.add('active');
        if (input) {
          input.value = chip.dataset.amount;
          input.dispatchEvent(new Event('input'));
        }
      });
    });
  }

  function clearActiveChips() {
    document.querySelectorAll('.wlt-chip.active').forEach(c => c.classList.remove('active'));
  }

  // =============================================
  // PAYMENT HANDLERS
  // =============================================
async function handleAddMoney(plan) {

    console.log('PLAN:', plan);

    try {

        showToast(`Preparing payment of ₹${plan.amount}...`);

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');

        if (!csrfToken) {
            throw new Error('CSRF token not found.');
        }

        const response = await fetch('/wallet/create-order', {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },

            body: JSON.stringify({
                amount: plan.amount
            })
        });

        const data = await response.json();

        console.log('Create order response:', data);

        if (!response.ok || !data.success) {

            throw new Error(
                data.message ||
                'Unable to create payment order.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Razorpay Options
        |--------------------------------------------------------------------------
        */

        const razorpayOptions = {

            key: data.razor_key,

            amount: data.amount,

            currency: data.currency,

            name: 'HimRishtey',

            description: 'Wallet Recharge',

            order_id: data.order_id,

            handler: async function (razorpayResponse) {

                console.log(
                    'Razorpay payment response:',
                    razorpayResponse
                );

                await verifyWalletPayment(
                    razorpayResponse,
                    plan.amount
                );
            },

            prefill: {
                name: window.memberName || '',
                email: window.memberEmail || '',
                contact: window.memberPhone || ''
            },

            theme: {
                color: '#6d4aff'
            },

            modal: {

                ondismiss: function () {

                    showToast(
                        'Payment cancelled.'
                    );
                }

            }

        };

        /*
        |--------------------------------------------------------------------------
        | Open Razorpay
        |--------------------------------------------------------------------------
        */

        if (typeof Razorpay === 'undefined') {
            throw new Error(
                'Razorpay Checkout script is not loaded.'
            );
        }

        const razorpay = new Razorpay(razorpayOptions);

        razorpay.open();

    } catch (error) {

        console.error(
            'Razorpay Error:',
            error
        );

        showToast(
            error.message ||
            'Unable to start payment.'
        );
    }
}

async function verifyWalletPayment(razorpayResponse,amount) 
{


    try {

        showToast(
            'Verifying payment...'
        );


        const response = await fetch(
            `/wallet/callback`,
            {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'Accept': 'application/json',

                    'X-CSRF-TOKEN':
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            .getAttribute('content')
                },

                body: JSON.stringify({

                    razorpay_order_id:
                        razorpayResponse.razorpay_order_id,

                    razorpay_payment_id:
                        razorpayResponse.razorpay_payment_id,

                    razorpay_signature:
                        razorpayResponse.razorpay_signature,

                    amount: amount
                })
            }
        );


        const data = await response.json();


        if (!response.ok || !data.success) {

            throw new Error(
                data.message ||
                'Payment verification failed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Wallet Balance
        |--------------------------------------------------------------------------
        */

        renderBalance(
            data.balance
        );


        showToast(
            '₹' + amount +
            ' added to your wallet successfully!'
        );


        /*
        |--------------------------------------------------------------------------
        | Reload transactions
        |--------------------------------------------------------------------------
        */

        setTimeout(() => {

            window.location.reload();

        }, 1500);


    } catch (error) {

        console.error(
            'Payment verification error:',
            error
        );

        showToast(
            error.message ||
            'Payment verification failed.'
        );
    }
}

  async function handleBuyOffer(offer) {

    try {

        if (!offer.id) {
            throw new Error('Invalid offer.');
        }

        showToast(`Preparing payment for ${offer.title}...`);

        /*
        |--------------------------------------------------------------------------
        | Create Razorpay Order
        |--------------------------------------------------------------------------
        */

        const response = await fetch(`wallet/buy-offer`, {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },

            body: JSON.stringify({
                offer_id: offer.id
            })
        });


        const data = await response.json();


        if (!response.ok || !data.success) {
            throw new Error(
                data.message || 'Unable to create payment order.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Razorpay Checkout
        |--------------------------------------------------------------------------
        */

        const options = {

            key: data.key,

            amount: data.amount,

            currency: data.currency,

            name: 'HimRishtey',

            description: data.description,

            order_id: data.order_id,


            prefill: {
                name: window.memberName || '',
                email: window.memberEmail || '',
                contact: window.memberPhone || ''
            },


            theme: {
                color: '#d92768'
            },


            handler: async function (razorpayResponse) {

                await verifyOfferPayment(
                    razorpayResponse
                );

            },


            modal: {

                ondismiss: function () {

                    showToast(
                        'Payment cancelled.'
                    );

                }

            }

        };


        const razorpay = new Razorpay(options);

        razorpay.open();


    } catch (error) {

        console.error(
            'Offer payment error:',
            error
        );

        showToast(
            error.message ||
            'Unable to start payment.'
        );
    }
}

async function verifyOfferPayment(razorpayResponse) {

    try {

        showToast('Verifying payment...');


        const response = await fetch(
            `wallet/callback`,
            {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'Accept': 'application/json',

                    'X-CSRF-TOKEN':
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            .getAttribute('content')
                },

                body: JSON.stringify({

                    razorpay_order_id:
                        razorpayResponse.razorpay_order_id,

                    razorpay_payment_id:
                        razorpayResponse.razorpay_payment_id,

                    razorpay_signature:
                        razorpayResponse.razorpay_signature

                })

            }
        );


        const data = await response.json();


        if (!response.ok || !data.success) {

            throw new Error(
                data.message ||
                'Payment verification failed.'
            );

        }


        showToast(
            data.message ||
            'Offer purchased successfully!'
        );


        /*
        |--------------------------------------------------------------------------
        | Reload wallet / offers
        |--------------------------------------------------------------------------
        */

        setTimeout(() => {

            window.location.reload();

        }, 1500);


    } catch (error) {

        console.error(
            'Offer verification error:',
            error
        );

        showToast(
            error.message ||
            'Payment verification failed.'
        );

    }
}
  // =============================================
  // TOAST
  // =============================================
  function showToast(message) {
    const toast = document.getElementById('wltToast');
    const msgEl = document.getElementById('wltToastMsg');
    if (!toast || !msgEl) return;
    msgEl.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3500);
  }

})();