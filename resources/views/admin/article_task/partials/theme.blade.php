<style>
    .article-task-shell {
        --task-ink: #102a43;
        --task-muted: #6b7a90;
        --task-line: rgba(148, 163, 184, 0.24);
        --task-soft: #f4f7fb;
        --task-panel: rgba(255, 255, 255, 0.92);
        --task-accent: #0f766e;
        --task-accent-deep: #115e59;
        --task-highlight: #2563eb;
        --task-danger: #c2410c;
        --task-danger-soft: #fff1eb;
        --task-success: #15803d;
        --task-success-soft: #eaf8ef;
        --task-warn: #b45309;
        --task-warn-soft: #fff7db;
        --task-shadow: 0 24px 70px rgba(15, 23, 42, 0.08);
        position: relative;
        padding-bottom: 28px;
    }

    .article-task-shell::before {
        content: "";
        position: absolute;
        inset: -24px -24px auto;
        height: 340px;
        border-radius: 28px;
        background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.18), transparent 32%),
                radial-gradient(circle at top right, rgba(15, 118, 110, 0.22), transparent 28%),
                linear-gradient(180deg, #f8fbff 0%, rgba(248, 251, 255, 0) 100%);
        z-index: 0;
        pointer-events: none;
    }

    .article-task-shell > * {
        position: relative;
        z-index: 1;
    }

    .task-hero {
        background:
                linear-gradient(135deg, rgba(15, 23, 42, 0.96) 0%, rgba(17, 94, 89, 0.92) 56%, rgba(37, 99, 235, 0.88) 100%);
        border-radius: 28px;
        padding: 32px;
        color: #fff;
        box-shadow: var(--task-shadow);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .task-hero-compact {
        padding-bottom: 26px;
    }

    .task-hero::after {
        content: "";
        position: absolute;
        right: -40px;
        top: -30px;
        width: 180px;
        height: 180px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .task-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.9);
        font-size: 12px;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 14px;
    }

    .task-hero-title {
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: #fff;
    }

    .task-hero-title-compact {
        font-size: 28px;
        margin-bottom: 6px;
    }

    .task-hero-copy {
        margin: 14px 0 0;
        max-width: 760px;
        color: rgba(255, 255, 255, 0.82);
        font-size: 15px;
        line-height: 1.75;
    }

    .task-hero-copy-compact {
        max-width: 820px;
        margin-top: 10px;
    }

    .task-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .task-hero-actions-compact {
        margin-top: 18px;
    }

    .task-btn-primary,
    .task-btn-secondary,
    .task-btn-ghost {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 46px;
        padding: 0 18px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        border: none;
    }

    .task-btn-primary {
        background: #fff;
        color: var(--task-ink);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);
    }

    .task-btn-primary:hover {
        color: var(--task-ink);
        transform: translateY(-1px);
    }

    .task-btn-secondary {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .task-btn-secondary:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.18);
    }

    .task-btn-ghost {
        background: var(--task-soft);
        color: var(--task-ink);
        border: 1px solid rgba(148, 163, 184, 0.24);
    }

    .task-btn-ghost:hover {
        color: var(--task-ink);
        background: #edf3fa;
    }

    .task-btn-accent {
        background: var(--task-accent);
        color: #fff;
        box-shadow: 0 8px 20px rgba(15, 118, 110, 0.22);
    }

    .task-btn-accent:hover {
        color: #fff;
        background: var(--task-accent-deep);
        transform: translateY(-1px);
    }

    .task-panel {
        background: var(--task-panel);
        border: 1px solid var(--task-line);
        border-radius: 24px;
        box-shadow: var(--task-shadow);
        backdrop-filter: blur(18px);
    }

    .task-panel-body {
        padding: 24px;
    }

    .task-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 24px 24px 0;
    }

    .task-section-title {
        margin: 0;
        color: var(--task-ink);
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .task-section-copy {
        margin: 6px 0 0;
        color: var(--task-muted);
        font-size: 14px;
        line-height: 1.7;
    }

    .task-metric {
        height: 100%;
        border-radius: 22px;
        padding: 22px;
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.05);
    }

    .task-metric-label {
        color: var(--task-muted);
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 12px;
    }

    .task-metric-value {
        color: var(--task-ink);
        font-size: 34px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 8px;
    }

    .task-metric-copy {
        color: var(--task-muted);
        font-size: 13px;
        margin: 0;
    }

    .task-form-label {
        display: block;
        color: var(--task-ink);
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .task-form-label span {
        color: var(--task-danger);
    }

    .task-form-control,
    .task-form-select,
    .task-form-textarea {
        width: 100%;
        min-height: 48px;
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, 0.28);
        background: rgba(255, 255, 255, 0.94);
        color: var(--task-ink);
        padding: 13px 16px;
        box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.03);
        transition: all 0.2s ease;
    }

    .task-form-textarea {
        min-height: 140px;
        resize: vertical;
    }

    .task-form-control:focus,
    .task-form-select:focus,
    .task-form-textarea:focus {
        outline: none;
        border-color: rgba(37, 99, 235, 0.45);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .task-hint {
        margin-top: 10px;
        color: var(--task-muted);
        font-size: 13px;
        line-height: 1.65;
    }

    .task-switch {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        align-items: flex-start;
        padding: 18px;
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 18px;
        background: rgba(244, 247, 251, 0.72);
        height: 100%;
    }

    .task-switch-emphasis {
        background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, rgba(244, 247, 251, 0.92) 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
    }

    .task-switch-title {
        margin: 0;
        color: var(--task-ink);
        font-size: 15px;
        font-weight: 600;
    }

    .task-switch-copy {
        margin: 6px 0 0;
        color: var(--task-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .task-switch-control {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        min-height: 52px;
        padding-left: 0;
        margin-left: 12px;
    }

    .task-switch-control .form-check-input {
        width: 48px;
        height: 28px;
        margin: 0;
        cursor: pointer;
        border: none;
        background-color: rgba(148, 163, 184, 0.55);
        box-shadow: none;
    }

    .task-switch-control .form-check-input:focus {
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .task-switch-control .form-check-input:checked {
        background-color: var(--task-accent);
    }

    .task-action-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid var(--task-line);
    }

    .task-action-copy {
        display: flex;
        flex-direction: column;
        gap: 4px;
        color: var(--task-muted);
        font-size: 13px;
        line-height: 1.65;
    }

    .task-action-copy strong {
        color: var(--task-ink);
        font-size: 14px;
        font-weight: 700;
    }

    .task-action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: flex-end;
    }

    .task-btn-accent {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 46px;
        padding: 0 20px;
        border: none;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .task-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 12px;
        letter-spacing: 0.01em;
    }

    .task-alert {
        border: none;
        border-radius: 18px;
        padding: 16px 18px;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.05);
    }

    .task-empty {
        padding: 56px 20px;
        text-align: center;
        color: var(--task-muted);
    }

    .task-empty i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 68px;
        height: 68px;
        border-radius: 24px;
        background: rgba(15, 118, 110, 0.1);
        color: var(--task-accent);
        font-size: 28px;
        margin-bottom: 16px;
    }

    @media (max-width: 991.98px) {
        .task-hero {
            padding: 24px;
        }

        .task-hero-title {
            font-size: 26px;
        }

        .task-panel-header,
        .task-panel-body {
            padding-left: 18px;
            padding-right: 18px;
        }

        .task-action-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .task-action-buttons {
            justify-content: stretch;
        }

        .task-action-buttons > * {
            flex: 1 1 100%;
        }
    }

    @media (max-width: 767.98px) {
        .task-switch {
            flex-direction: column;
        }

        .task-switch-control {
            margin-left: 0;
            min-height: auto;
        }
    }

    /* ── 定时发布 ─────────────────────────────────────── */
    .sched-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 16px 18px;
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 16px;
        background: linear-gradient(180deg,
        rgba(255, 255, 255, 0.94) 0%,
        rgba(244, 247, 251, 0.92) 100%);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .sched-row.is-active {
        border-color: rgba(15, 118, 110, 0.38);
        background: linear-gradient(180deg,
        rgba(15, 118, 110, 0.07) 0%,
        rgba(15, 118, 110, 0.02) 100%);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
    }

    .sched-row-text { flex: 1; min-width: 0; }

    .sched-row-title {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: var(--task-ink);
    }

    .sched-row-copy {
        margin: 5px 0 0;
        font-size: 13px;
        color: var(--task-muted);
        line-height: 1.6;
    }

    .sched-toggle {
        flex-shrink: 0;
        width: 48px;
        height: 28px;
        margin: 0;
        cursor: pointer;
        border: none;
        border-radius: 999px;
        background-color: rgba(148, 163, 184, 0.55);
        box-shadow: none;
        transition: background-color 0.2s;
    }

    .sched-toggle:checked { background-color: var(--task-accent); }
    .sched-toggle:focus   { box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12); outline: none; }

    .sched-panel {
        background: rgba(15, 118, 110, 0.04);
        border: 1.5px solid rgba(15, 118, 110, 0.2);
        border-radius: 14px;
        padding: 16px 18px;
        animation: schedIn 0.18s ease;
    }

    @keyframes schedIn {
        from { opacity: 0; transform: translateY(-5px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .sched-inner {
        display: flex;
        align-items: stretch;
        gap: 16px;
    }

    .sched-field { flex: 1; min-width: 0; }

    .sched-preview {
        flex-shrink: 0;
        width: 144px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        background: linear-gradient(135deg, var(--task-accent) 0%, var(--task-highlight) 100%);
        border-radius: 12px;
        padding: 14px 10px;
        color: #fff;
        text-align: center;
        box-shadow: 0 4px 14px rgba(15, 118, 110, 0.22);
    }

    .sched-preview-icon { font-size: 16px; opacity: 0.72; }
    .sched-preview-num  { font-size: 36px; font-weight: 700; line-height: 1; letter-spacing: -0.04em; }
    .sched-preview-unit { font-size: 12px; opacity: 0.8; }
    .sched-preview-note { font-size: 11px; opacity: 0.65; line-height: 1.4; margin-top: 5px; }

    @media (max-width: 767.98px) {
        .sched-inner   { flex-direction: column; }
        .sched-preview { width: 100%; }
    }
</style>
