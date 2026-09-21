/* ===========================================================
   HimRishtey - Edit Profile
   =========================================================== */

(() => {

"use strict";

/* ===========================================================
   Configuration
=========================================================== */

const CONFIG = {

    updateUrl: "update-profile",

    toastDuration: 3500

};

const SECTION_FIELDS = {
    "basic-info": ["about_me", "profile_created_for", "birth_date", "birth_time", "height", "religion", "cast", "marital_status", "country_living_in", "state_living_in", "city_living_in"],
    astro: ["manglik", "birth_place"],
    education: ["about_my_education", "education", "any_other_qualifications", "employed_in", "organization_name", "job_location", "occupation", "annual_income"],
    family: ["family_status", "native_place", "father_name", "father_occupation", "mother_name", "mother_occupation", "no_of_brothers", "no_of_sisters", "married_brothers", "married_sisters", "about_my_family"],
    lifestyle: ["diet", "is_smoking", "is_drinking", "any_disability"],
    religion: ["gotra", "sub_cast"],
    preferences: ["looking_for", "partner_religion", "partner_cast", "partner_mothertongue", "partner_education", "partner_occupation", "partner_age_from", "partner_age_to", "partner_height_from", "partner_height_to", "partner_annual_income_from", "partner_annual_income_to", "is_partner_smoking", "is_partner_drinking", "partner_diet", "is_partner_manglik", "about_my_partner"],
    contact: ["mobile_number", "alternate_number", "whatsapp_number", "email"]
};


/* ===========================================================
   DOM Cache
=========================================================== */

const DOM = {};


/* ===========================================================
   Cache DOM Elements
=========================================================== */

function cacheDOM() {

    DOM.csrf = document.querySelector(
        'meta[name="csrf-token"]'
    )?.content;

    DOM.sidebarTabs = document.querySelectorAll(
        ".ep-tab-btn[data-tab]"
    );

    DOM.mobileTabs = document.querySelectorAll(
        ".ep-mobile-tab[data-tab]"
    );

    DOM.panels = document.querySelectorAll(
        '.ep-tab-panel[id^="tab-"]'
    );

    DOM.forms = document.querySelectorAll(
        ".ep-form[data-section]"
    );

    DOM.completionBar =
        document.querySelector(".ep-completion-bar");

    DOM.completionText =
        document.querySelector(".ep-completion-pct");

    DOM.successToast =
        document.getElementById("epSuccessToast");

    DOM.toastMessage =
        document.getElementById("epToastMsg");

    DOM.unsavedToast =
        document.getElementById("epUnsavedToast");

}


/* ===========================================================
   Helpers
=========================================================== */

function createIcons() {

    if (window.lucide) {
        lucide.createIcons();
    }

}

function capitalize(str = "") {

    if (!str) return "";

    return str.charAt(0).toUpperCase() + str.slice(1);

}

function getCSRFToken() {

    return DOM.csrf;

}


/* ===========================================================
   API
=========================================================== */

const api = {

    async post(url, body) {

        return fetch(url, {

            method: "POST",

            headers: {

                "X-CSRF-TOKEN": getCSRFToken(),

                "X-Requested-With": "XMLHttpRequest",

                "Accept": "application/json"

            },

            body

        });

    }

};

/* ===========================================================
   Tab Manager
=========================================================== */

const TabManager = {

    init() {

        DOM.sidebarTabs.forEach(tab => {

            tab.addEventListener("click", () => {

                this.switch(tab.dataset.tab);

            });

        });

        DOM.mobileTabs.forEach(tab => {

            tab.addEventListener("click", () => {

                this.switch(tab.dataset.tab);

            });

        });

    },

    switch(tabName) {

        // Sidebar
        DOM.sidebarTabs.forEach(tab => {

            const active = tab.dataset.tab === tabName;

            tab.classList.toggle("active", active);

            tab.setAttribute(
                "aria-selected",
                active
            );

        });

        // Mobile
        DOM.mobileTabs.forEach(tab => {

            tab.classList.toggle(
                "active",
                tab.dataset.tab === tabName
            );

        });

        // Panels
        DOM.panels.forEach(panel => {

            const active =
                panel.id === `tab-${tabName}`;

            panel.classList.toggle(
                "active",
                active
            );

            if (active) {

                panel.removeAttribute("hidden");

            } else {

                panel.setAttribute("hidden", "");

            }

        });

        this.toggleUnsavedToast(tabName);

        createIcons();

    },

    markDirty(section) {

        const tab = document.querySelector(
            `.ep-tab-btn[data-tab="${section}"]`
        );

        if (!tab) return;

        tab.dataset.dirty = "true";

        this.toggleUnsavedToast(section);

    },

    clearDirty(section) {

        const tab = document.querySelector(
            `.ep-tab-btn[data-tab="${section}"]`
        );

        if (!tab) return;

        delete tab.dataset.dirty;

        this.toggleUnsavedToast(section);

    },

    toggleUnsavedToast(tabName) {

        if (!DOM.unsavedToast) return;

        const dirty = document.querySelector(
            `.ep-tab-btn[data-tab="${tabName}"][data-dirty="true"]`
        );

        if (dirty) {

            DOM.unsavedToast.removeAttribute("hidden");

        } else {

            DOM.unsavedToast.setAttribute(
                "hidden",
                ""
            );

        }

    },

    updateStatus(section, status) {

        const tab = document.querySelector(
            `.ep-tab-btn[data-tab="${section}"]`
        );

        if (!tab) return;

        const statusBox =
            tab.querySelector(".ep-tab-status");

        if (!statusBox) return;

        statusBox.className =
            `ep-tab-status ${status}`;

        statusBox.title =
            status === "complete"
                ? "Complete"
                : "Incomplete";

        statusBox.innerHTML =
            status === "complete"

                ? '<i data-lucide="check-circle" width="14" height="14"></i>'

                : '<i data-lucide="circle" width="14" height="14"></i>';

        createIcons();

        this.updateCompletion();

    },

    updateCompletion() {

        const total =
            DOM.sidebarTabs.length;

        const completed =
            document.querySelectorAll(
                ".ep-tab-status.complete"
            ).length;

        const percent = total
            ? Math.round((completed / total) * 100)
            : 0;

        if (DOM.completionBar) {

            DOM.completionBar.style.width =
                `${percent}%`;

        }

        if (DOM.completionText) {

            DOM.completionText.textContent =
                `${percent}%`;

        }

    }

};

/* ===========================================================
   Toast Manager
=========================================================== */

const Toast = {

    timer: null,

    show(message, type = "success") {

        if (!DOM.successToast || !DOM.toastMessage) {
            return;
        }

        DOM.toastMessage.textContent = message;

        const backgrounds = {
            success: "var(--color-text)",
            error: "var(--color-error, #a12c7b)",
            warning: "#f59e0b",
            info: "#2563eb"
        };

        DOM.successToast.style.background =
            backgrounds[type] || backgrounds.success;

        DOM.successToast.classList.add("show");

        clearTimeout(this.timer);

        this.timer = setTimeout(() => {

            this.hide();

        }, CONFIG.toastDuration);

    },

    hide() {

        DOM.successToast?.classList.remove("show");

    },

    success(message) {

        this.show(message, "success");

    },

    error(message) {

        this.show(message, "error");

    },

    warning(message) {

        this.show(message, "warning");

    },

    info(message) {

        this.show(message, "info");

    }

};

/* ==================================================
   VALIDATION MANAGER
================================================== */

const ValidationManager = {

    init() {
        // Reserved for future use
    },

    validateForm(form) {

        let valid = true;

        // Clear previous errors
        form.querySelectorAll(".error").forEach(field => {
            field.classList.remove("error");
        });

        // Required fields
        form.querySelectorAll("[required]").forEach(field => {

            if (!field.value.trim()) {

                this.showError(field);

                if (valid) field.focus();

                valid = false;

            }

        });

        // Phone validation
        const phoneField = form.querySelector("#mobile_number");

        if (
            phoneField &&
            phoneField.value.trim() &&
            !this.isValidPhone(phoneField.value)
        ) {

            this.showError(
                phoneField,
                "Please enter a valid phone number."
            );

            if (valid) phoneField.focus();

            valid = false;

        }

        // Gotra validation
        const gotraField = form.querySelector("#gotra");

        if (
            gotraField &&
            !gotraField.value.trim()
        ) {

            this.showError(
                gotraField,
                "Gotra is required."
            );

            if (valid) gotraField.focus();

            valid = false;

        }

        return valid;

    },

    showError(field, message = "") {

        if (!field) return;

        field.classList.add("error");

        if (message) {
            Toast.show(message, true);
        }

    },

    clearError(field) {

        if (!field) return;

        field.classList.remove("error");

    },

    isEmpty(field) {

        return !field.value.trim();

    },

    isValidPhone(number) {

        const phone = number.replace(/\D/g, "");

        return phone.length >= 10 &&
               phone.length <= 13;

    },

    isValidEmail(email) {

        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

    },

    isNumber(value) {

        return /^\d+$/.test(value);

    }

};

/* ==========================================
   FORM MANAGER
========================================== */

const FormManager = {

    init() {

        DOM.forms.forEach(form => {

            const section = form.dataset.section;

            // Mark dirty and refresh completion status when the user changes input
            const refreshStatus = () => {
                const status = StatusManager.isComplete(form)
                    ? "complete"
                    : "incomplete";

                StatusManager.update(section, status);
            };

            form.addEventListener("input", () => {
                TabManager.markDirty(section);
                refreshStatus();
            });

            form.addEventListener("change", () => {
                TabManager.markDirty(section);
                refreshStatus();
            });

            // Submit
            form.addEventListener("submit", e => {
                e.preventDefault();
                this.submit(form);
            });

        });

    },

    async submit(form) {

        const section = form.dataset.section;

        if (!ValidationManager.validateForm(form)) {
            return;
        }

        const saveBtn = form.querySelector(".ep-save-btn");

        this.setLoading(saveBtn);

        try {

            const response = await this.save(form);

            this.success(response, section, saveBtn);

        }
        catch (error) {

            this.failed(error, saveBtn);

        }

    },

    async save(form) {

        const response = await fetch(CONFIG.updateUrl, {

            method: "POST",

            headers: {

                "X-CSRF-TOKEN": DOM.csrf,

                "X-Requested-With": "XMLHttpRequest",

                "Accept": "application/json"

            },

            body: new FormData(form)

        });

        const data = await response.json();

        if (!response.ok) {

            throw data;

        }

        return data;

    },

    setLoading(button) {

        if (!button) return;

        button.dataset.original = button.innerHTML;

        button.disabled = true;

        button.classList.add("saving");

        button.innerHTML = `
            <i data-lucide="loader-circle" width="16" height="16"></i>
            Saving...
        `;

        createIcons();

    },

    resetButton(button) {

        if (!button) return;

        button.disabled = false;

        button.classList.remove("saving");

        button.innerHTML = button.dataset.original;

        createIcons();

    },

    success(response, section, button) {

        button.innerHTML = `
            <i data-lucide="check-circle" width="16" height="16"></i>
            Saved!
        `;

        createIcons();

        TabManager.clearDirty(section);

        const form = document.querySelector(`.ep-form[data-section="${section}"]`);
        const nextStatus = StatusManager.isComplete(form) ? "complete" : "incomplete";
        StatusManager.update(section, nextStatus);

        ToastManager.success(response.message || 'Profile updated successfully.');

        setTimeout(() => {

            this.resetButton(button);

        }, 2000);

    },

    failed(error, button) {

        button.innerHTML = `
            <i data-lucide="triangle-alert" width="16" height="16"></i>
            Save Failed
        `;

        createIcons();

        if (error.errors) {

            const firstError = Object.values(error.errors)
                .flat()[0];

            ToastManager.error(firstError);

        }
        else {

            ToastManager.error(error.message || 'Something went wrong. Please try again later...!');

        }

        setTimeout(() => {

            this.resetButton(button);

        }, 2000);

    }

};

/* ==========================================
   STATUS MANAGER
========================================== */

const StatusManager = {

    init() {
        this.refreshAll();
    },

    refreshAll() {
        DOM.forms.forEach(form => {
            const section = form.dataset.section;
            this.update(section, this.isComplete(form) ? "complete" : "incomplete", false);
        });

        this.updateOverall();
    },

    isComplete(form) {
        if (!form) return false;

        const fieldNames = SECTION_FIELDS[form.dataset.section] || [];

        if (fieldNames.length === 0) {
            return false;
        }

        const complete = fieldNames.every(name => {
            const fields = [...form.querySelectorAll(`[name="${name}"], [name="${name}[]"]`)];

            if (fields.length === 0) return false;

            const selectableFields = fields.filter(field => field.type === "checkbox" || field.type === "radio");
            if (selectableFields.length > 0) {
                return selectableFields.some(field => field.checked);
            }

            return fields.some(field => {
                const value = field.value.trim();
                return value !== "" && value !== "Any";
            });
        });

        if (!complete) return false;

        const disability = form.querySelector('[name="any_disability"]');
        const disabilityDetail = form.querySelector('[name="disability_detail"]');

        return disability?.value !== "Yes" || Boolean(disabilityDetail?.value.trim());
    },

    update(section, status = "complete", updateOverall = true) {

        const tab = document.querySelector(
            `.ep-tab-btn[data-tab="${section}"]`
        );

        if (!tab) return;

        const badge = tab.querySelector(".ep-tab-status");

        if (!badge) return;

        badge.className = `ep-tab-status ${status}`;

        badge.title =
            status === "complete"
                ? "Complete"
                : "Incomplete";

        badge.innerHTML = this.getIcon(status);

        createIcons();

        if (updateOverall) {
            this.updateOverall();
        }

    },

    updateOverall() {

        // const totalTabs = DOM.sidebarTabs.length;

        // if (!totalTabs) return;

        // const completedTabs = document.querySelectorAll(
        //     ".ep-tab-status.complete"
        // ).length;

        // const percentage = Math.round(
        //     (completedTabs / totalTabs) * 100
        // );

        // if (DOM.completionBar) {

        //     DOM.completionBar.style.width = `${percentage}%`;

        // }

        // if (DOM.completionText) {

        //     DOM.completionText.textContent = `${percentage}%`;

        // }
        return;

    },

    getIcon(status) {

        if (status === "complete") {

            return `
                <i data-lucide="check-circle"
                   width="14"
                   height="14"></i>
            `;

        }

        return `
            <i data-lucide="circle"
               width="14"
               height="14"></i>
        `;

    }

};

/* ==========================================
   TOAST MANAGER
========================================== */

const ToastManager = {

    timer: null,

    show(message, type = "success") {

        if (!DOM.successToast || !DOM.toastMessage) return;

        DOM.toastMessage.textContent = message;

        DOM.successToast.style.background = this.getColor(type);

        DOM.successToast.classList.add("show");

        clearTimeout(this.timer);

        this.timer = setTimeout(() => {

            this.hide();

        }, CONFIG.toastDuration);

    },

    hide() {

        if (!DOM.successToast) return;

        DOM.successToast.classList.remove("show");

    },

    success(message) {

        this.show(message, "success");

    },

    error(message) {

        this.show(message, "error");

    },

    warning(message) {

        this.show(message, "warning");

    },

    info(message) {

        this.show(message, "info");

    },

    getColor(type) {

        switch (type) {

            case "error":
                return "var(--color-error, #a12c7b)";

            case "warning":
                return "#f59e0b";

            case "info":
                return "#2563eb";

            default:
                return "var(--color-text)";
        }

    }

};

/* ==========================================
   MULTI SELECT MANAGER
========================================== */

const MultiSelectManager = {

    init() {

        this.triggers = document.querySelectorAll(
            ".ep-multiselect-trigger"
        );

        this.triggers.forEach(trigger => {

            this.register(trigger);

        });

        document.addEventListener(
            "click",
            this.handleOutsideClick.bind(this)
        );

    },

    register(trigger) {

        const target = trigger.dataset.target;

        const dropdown = document.getElementById(
            `${target}-dropdown`
        );

        const hiddenInput = document.getElementById(target);

        const display = document.getElementById(
            `${target}-display`
        );

        if (!dropdown || !hiddenInput || !display) {
            return;
        }

        trigger.addEventListener("click", () => {

            this.toggle(trigger, dropdown);

        });

        trigger.addEventListener("keydown", e => {

            if (e.key === "Enter" || e.key === " ") {

                e.preventDefault();

                this.toggle(trigger, dropdown);

            }

        });

        dropdown
            .querySelectorAll('input[type="checkbox"]')
            .forEach(cb => {

                cb.addEventListener("change", () => {

                    this.updateSelection(
                        trigger,
                        dropdown,
                        hiddenInput,
                        display
                    );

                });

            });

    },

    toggle(trigger, dropdown) {

        const isOpen = !dropdown.hidden;

        this.closeAll();

        if (!isOpen) {

            dropdown.removeAttribute("hidden");

            trigger.classList.add("open");

        }

    },

    updateSelection(
        trigger,
        dropdown,
        hiddenInput,
        display
    ) {

        const values = [
            ...dropdown.querySelectorAll(
                'input[type="checkbox"]:checked'
            )
        ].map(cb => cb.value);

        if (values.length === 0) {

            hiddenInput.value = "Any";

            display.textContent = "Any";

        } else {

            hiddenInput.value = values.join(",");

            display.textContent =
                this.formatDisplay(values);

        }

        const panel = trigger.closest(".ep-tab-panel");

        if (panel) {

            const section =
                panel.id.replace("tab-", "");

            TabManager.markDirty(section);

        }

    },

    formatDisplay(values) {

        if (values.length <= 2) {

            return values.join(", ");

        }

        return `${values
            .slice(0, 2)
            .join(", ")} +${values.length - 2} more`;

    },

    closeAll() {

        document
            .querySelectorAll(".ep-multiselect-dropdown")
            .forEach(dropdown => {

                dropdown.setAttribute("hidden", "");

            });

        document
            .querySelectorAll(".ep-multiselect-trigger")
            .forEach(trigger => {

                trigger.classList.remove("open");

            });

    },

    handleOutsideClick(e) {

        if (
            e.target.closest(".ep-multiselect-trigger") ||
            e.target.closest(".ep-multiselect-dropdown")
        ) {
            return;
        }

        this.closeAll();

    }

};

/* ==========================================
   RANGE SLIDER MANAGER
========================================== */

const RangeSliderManager = {

    init() {

        const heightLabels = this.buildHeightLabels();

        this.register({

            from: "age_from",
            to: "age_to",
            label: "age-range-label",
            formatter: value => `${Math.round(value)}`
        });

        this.register({

            from: "height_from",
            to: "height_to",
            label: "height-range-label",
            formatter: value =>
                heightLabels[Math.round(value) - 1] || value

        });

        this.register({

            from: "income_from",
            to: "income_to",
            label: "income-range-label",
            formatter: value => `${Math.round(value)} LPA`

        });

    },

    register(config) {

        const from = document.getElementById(config.from);
        const to = document.getElementById(config.to);
        const label = document.getElementById(config.label);

        if (!from || !to || !label) {
            return;
        }

        const update = () => {

            let min = parseInt(from.value);
            let max = parseInt(to.value);

            if (min > max) {

                if (document.activeElement === from) {

                    from.value = max;
                    min = max;

                } else {

                    to.value = min;
                    max = min;

                }

            }

            label.textContent =
                `${config.formatter(min)} – ${config.formatter(max)}`;

            // Do NOT mark dirty on initial render. Marking should only occur
            // when the user interacts with the range inputs.

        };

        from.addEventListener("input", () => {
            update();
            this.markDirty(from);
        });

        to.addEventListener("input", () => {
            update();
            this.markDirty(to);
        });

        // Initialize display without marking the form dirty
        update();

    },

    markDirty(element) {

        const panel = element.closest(".ep-tab-panel");

        if (!panel) return;

        const section = panel.id.replace("tab-", "");

        TabManager.markDirty(section);

    },

    buildHeightLabels() {

        const labels = [];

        let feet = 4;
        let inches = 6;

        for (let i = 0; i < 28; i++) {

            labels.push(`${feet}'${inches}"`);

            inches++;

            if (inches === 12) {

                feet++;
                inches = 0;

            }

        }

        return labels;

    }

};

/* ==========================================
   CONDITION MANAGER
========================================== */

const ConditionManager = {

    init() {

        this.initMaritalStatus();
        this.initSiblings();
        this.initDisability();

    },

    /* ==========================================
       MARITAL STATUS
    ========================================== */

    initMaritalStatus() {

        const maritalStatus = document.getElementById("marital_status");
        const childrenWrap = document.getElementById("children-wrap");
        const childrenSelect = document.getElementById("no_of_child");

        if (!maritalStatus || !childrenWrap) {
            return;
        }

        const update = () => {

            const show =
                maritalStatus.value &&
                maritalStatus.value !== "Never Married";

            if (show) {

                childrenWrap.removeAttribute("hidden");

            } else {

                childrenWrap.setAttribute("hidden", "");

                if (childrenSelect) {
                    childrenSelect.value = "";
                }

            }

            // Do not mark dirty on initial setup. Only mark when user changes value.

        };

        maritalStatus.addEventListener("change", () => {
            update();
            this.markDirty(maritalStatus);
        });

        // Initialize state without marking dirty
        update();

    },

    /* ==========================================
        BROTHER / SISTER
    ========================================== */

        initSiblings() {

            const brother = document.getElementById("no_of_brothers");
            const sister = document.getElementById("no_of_sisters");

            const marriedBrother = document.getElementById("married_brothers");
            const marriedSister = document.getElementById("married_sisters");
            const marriedBrotherZero = document.getElementById("married_brothers_zero");
            const marriedSisterZero = document.getElementById("married_sisters_zero");

            if (!brother && !sister) {
                return;
            }

            const updateBrother = () => {

                if (!marriedBrother || !brother) {
                    return;
                }

                const count = parseInt(brother.value, 10) || 0;

                marriedBrother.disabled = count === 0;
                if (marriedBrotherZero) {
                    marriedBrotherZero.disabled = count !== 0;
                }

                if (count === 0) {
                    marriedBrother.value = "0";
                }

            };

            const updateSister = () => {

                if (!marriedSister || !sister) {
                    return;
                }

                const count = parseInt(sister.value, 10) || 0;

                marriedSister.disabled = count === 0;
                if (marriedSisterZero) {
                    marriedSisterZero.disabled = count !== 0;
                }

                if (count === 0) {
                    marriedSister.value = "0";
                }

            };

            // Initial state
            updateBrother();
            updateSister();

            // Update when user changes values
            brother?.addEventListener("change", () => {

                updateBrother();

                this.markDirty(brother);

            });

            sister?.addEventListener("change", () => {

                updateSister();

                this.markDirty(sister);

            });

        },

    /* ==========================================
       DISABILITY
    ========================================== */

    initDisability() {

        const disability = document.getElementById("any_disability");
        const detailWrap = document.getElementById("disability-detail-wrap");
        const detail = document.getElementById("disability_detail");

        if (!disability || !detailWrap) {
            return;
        }

        const update = () => {

            if (disability.value === "Yes") {

                detailWrap.removeAttribute("hidden");

            } else {

                detailWrap.setAttribute("hidden", "");

                if (detail) {
                    detail.value = "";
                }

            }

            // Do not mark dirty on initial setup; only mark on user interaction

        };

        disability.addEventListener("change", () => {
            update();
            this.markDirty(disability);
        });

        // Initialize state without marking dirty
        update();

    },

    /* ==========================================
       MARK DIRTY
    ========================================== */

    markDirty(element) {

        const panel = element.closest(".ep-tab-panel");

        if (!panel) return;

        const section = panel.id.replace("tab-", "");

        TabManager.markDirty(section);

    }

};


/* ===========================================================
   Init
=========================================================== */

document.addEventListener("DOMContentLoaded", init);

function init() {

    cacheDOM();

    createIcons();

    TabManager.init();

    ValidationManager.init();

    FormManager.init();

    MultiSelectManager.init();

    RangeSliderManager.init();

    ConditionManager.init();

    StatusManager.init();

    TabManager.switch('basic-info');

}


})();
