{{-- Shared Arena Belajar game-stage styles (visual language Jagat Misi) --}}
<style>
[x-cloak]{display:none!important}

.arena-stage {
    --arena-navy: #12345b;
    --arena-teal: #00a99d;
    --arena-amber: #f5a524;
    --arena-rose: #e85d75;
    --arena-leaf: #5ba85b;
    --arena-ink: #172033;
    --arena-muted: #667085;
    --arena-line: #d9dee8;
    --arena-bg: #f3f6fb;
    --arena-shadow: 0 18px 45px rgba(18, 52, 91, 0.12);
    --arena-shadow-lg: 0 28px 60px rgba(18, 52, 91, 0.18);
    --arena-warn: #f5a524;
    --arena-ok: #5ba85b;
    --arena-bad: #e85d75;
    --arena-sky: #7ec8ff;
    --arena-play: #00c853;
    position: relative;
    margin: -0.25rem -0.15rem 0;
    padding: 0.25rem 0.15rem 1.5rem;
}

/* ─── Game lobby (Roblox-style Discover) ─── */
.arena-lobby {
    --lobby-font: 'Fredoka', 'Plus Jakarta Sans', system-ui, sans-serif;
    margin: -1rem -1.25rem 0;
    padding: 0 1rem 2.5rem;
    min-height: 72vh;
    isolation: isolate;
    overflow: hidden;
}
@media (min-width: 641px) {
    .arena-lobby { margin: -1.25rem -1.75rem 0; padding: 0 1.5rem 2.75rem; }
}
.arena-lobby, .arena-lobby button, .arena-lobby a, .arena-lobby input, .arena-lobby select {
    font-family: var(--lobby-font);
}

.arena-lobby-world {
    position: absolute;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
}
.arena-lobby-sky {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 80% 50% at 50% -10%, #fff8e8 0%, transparent 55%),
        radial-gradient(circle at 12% 70%, rgba(0, 194, 178, 0.28), transparent 42%),
        radial-gradient(circle at 88% 30%, rgba(255, 176, 32, 0.22), transparent 40%),
        linear-gradient(180deg, #9ad4ff 0%, #c9ebff 38%, #e8f4ff 70%, #f4f7fb 100%);
}
.dark .arena-lobby-sky {
    background:
        radial-gradient(ellipse 80% 50% at 50% -10%, rgba(255, 176, 32, 0.18), transparent 55%),
        radial-gradient(circle at 12% 70%, rgba(0, 169, 157, 0.22), transparent 42%),
        linear-gradient(180deg, #0a1a2e 0%, #10253d 45%, #0f172a 100%);
}
.arena-lobby-grid {
    position: absolute;
    inset: 45% 0 0;
    background-image:
        linear-gradient(rgba(18, 52, 91, 0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(18, 52, 91, 0.06) 1px, transparent 1px);
    background-size: 48px 48px;
    transform: perspective(400px) rotateX(58deg) scale(1.4);
    transform-origin: center top;
    mask-image: linear-gradient(180deg, rgba(0,0,0,.55), transparent 85%);
}
.dark .arena-lobby-grid {
    background-image:
        linear-gradient(rgba(148, 163, 184, 0.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(148, 163, 184, 0.08) 1px, transparent 1px);
}

.arena-float-block {
    position: absolute;
    width: 2.4rem;
    height: 2.4rem;
    border-radius: .55rem;
    box-shadow:
        inset 0 2px 0 rgba(255,255,255,.35),
        0 8px 0 rgba(0,0,0,.12),
        0 14px 22px rgba(18, 52, 91, 0.18);
    animation: arena-block-float 5.5s ease-in-out infinite;
}
.arena-fb-a { left: 6%; top: 18%; background: linear-gradient(145deg, #00c2b2, #008f84); animation-delay: 0s; }
.arena-fb-b { right: 10%; top: 14%; width: 1.8rem; height: 1.8rem; background: linear-gradient(145deg, #ffb020, #e09410); animation-delay: .8s; }
.arena-fb-c { left: 14%; top: 52%; width: 1.5rem; height: 1.5rem; background: linear-gradient(145deg, #ff6b8a, #d94a68); animation-delay: 1.4s; }
.arena-fb-d { right: 16%; top: 48%; background: linear-gradient(145deg, #4da3ff, #2276d2); animation-delay: 2s; }
.arena-float-coin {
    position: absolute;
    width: 1.15rem;
    height: 1.15rem;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 30%, #ffe9a8, #ffb020 55%, #d48900);
    box-shadow: 0 0 0 3px rgba(255,176,32,.25), 0 6px 12px rgba(212,137,0,.35);
    animation: arena-coin-spin 3.8s linear infinite;
}
.arena-fc-a { left: 42%; top: 22%; }
.arena-fc-b { right: 28%; top: 58%; animation-delay: 1.2s; }
@keyframes arena-block-float {
    0%, 100% { transform: translateY(0) rotate(-6deg); }
    50% { transform: translateY(-14px) rotate(8deg); }
}
@keyframes arena-coin-spin {
    0% { transform: rotateY(0deg) translateY(0); }
    50% { transform: rotateY(180deg) translateY(-8px); }
    100% { transform: rotateY(360deg) translateY(0); }
}

.arena-lobby-hud {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-top: 1rem;
    margin-bottom: .5rem;
}
.arena-hud-back {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    max-width: 55%;
    min-height: 2.5rem;
    padding: .45rem .9rem;
    border-radius: 999px;
    background: rgba(255,255,255,.82);
    border: 2px solid rgba(18, 52, 91, 0.1);
    color: var(--arena-navy);
    font-weight: 700;
    font-size: .8rem;
    box-shadow: 0 6px 0 rgba(18, 52, 91, 0.08);
    transition: transform .12s ease;
}
.dark .arena-hud-back {
    background: rgba(15, 23, 42, .85);
    border-color: #334155;
    color: #e2e8f0;
    box-shadow: 0 6px 0 rgba(0,0,0,.25);
}
.arena-hud-back:hover { transform: translateY(1px); box-shadow: 0 4px 0 rgba(18, 52, 91, 0.08); }
.arena-hud-player {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .4rem .7rem .4rem .4rem;
    border-radius: 999px;
    background: rgba(255,255,255,.9);
    border: 2px solid rgba(18, 52, 91, 0.1);
    box-shadow: 0 6px 0 rgba(18, 52, 91, 0.08);
}
.dark .arena-hud-player {
    background: rgba(15, 23, 42, .9);
    border-color: #334155;
    box-shadow: 0 6px 0 rgba(0,0,0,.25);
}
.arena-hud-avatar {
    width: 2.35rem;
    height: 2.35rem;
    border-radius: .85rem;
    display: grid;
    place-items: center;
    font-weight: 800;
    font-size: .85rem;
    color: #fff;
    background: linear-gradient(145deg, #00c2b2, #0b3d6e);
    box-shadow: inset 0 2px 0 rgba(255,255,255,.25);
}
.arena-hud-name {
    margin: 0;
    font-size: .82rem;
    font-weight: 700;
    color: var(--arena-ink);
    max-width: 9rem;
}
.dark .arena-hud-name { color: #f1f5f9; }
.arena-hud-role {
    margin: 0;
    font-size: .65rem;
    font-weight: 700;
    color: var(--arena-muted);
    text-transform: uppercase;
    letter-spacing: .04em;
}

.arena-lobby-welcome {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 1.25rem 0 1.5rem;
    opacity: 0;
    transform: translateY(18px) scale(.98);
    transition: opacity .55s ease, transform .55s cubic-bezier(.2,.9,.2,1);
}
.arena-lobby-welcome.is-in {
    opacity: 1;
    transform: translateY(0) scale(1);
}
.arena-lobby-mascot {
    position: relative;
    width: 7.5rem;
    height: 7.5rem;
    margin: 0 auto 1rem;
}
.arena-mascot-ring {
    position: absolute;
    inset: 0;
    border-radius: 1.6rem;
    border: 3px dashed rgba(0, 169, 157, 0.45);
    animation: arena-spin 18s linear infinite;
}
.arena-mascot-core {
    position: absolute;
    inset: .55rem;
    border-radius: 1.35rem;
    display: grid;
    place-items: center;
    color: #fff;
    font-size: 2rem;
    font-weight: 800;
    background:
        radial-gradient(circle at 30% 25%, rgba(255,255,255,.45), transparent 40%),
        linear-gradient(155deg, #00c2b2 0%, #0b3d6e 55%, #e85d75 120%);
    box-shadow:
        inset 0 3px 0 rgba(255,255,255,.3),
        0 10px 0 rgba(11, 61, 110, 0.35),
        0 18px 32px rgba(11, 61, 110, 0.35);
    animation: arena-float 3.2s ease-in-out infinite;
}
.arena-mascot-badge {
    position: absolute;
    left: 50%;
    bottom: -.35rem;
    transform: translateX(-50%);
    padding: .2rem .55rem;
    border-radius: 999px;
    background: var(--arena-play);
    color: #fff;
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .06em;
    box-shadow: 0 3px 0 #00963e;
}
.arena-lobby-kicker {
    margin: 0 0 .35rem;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #0b5e8a;
}
.dark .arena-lobby-kicker { color: #7dd3fc; }
.arena-lobby-brand {
    margin: 0;
    font-size: clamp(2.4rem, 7vw, 3.6rem);
    font-weight: 700;
    line-height: 1;
    letter-spacing: -0.02em;
    color: var(--arena-navy);
    text-shadow:
        0 3px 0 rgba(255,255,255,.65),
        0 8px 0 rgba(18, 52, 91, 0.12);
}
.dark .arena-lobby-brand {
    color: #f8fafc;
    text-shadow: 0 4px 0 rgba(0,0,0,.35);
}
.arena-lobby-tagline {
    margin: .65rem auto 0;
    max-width: 26rem;
    font-size: .95rem;
    font-weight: 600;
    color: #3d5678;
}
.dark .arena-lobby-tagline { color: #94a3b8; }

.arena-lobby-stats {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .55rem;
    margin-top: 1.15rem;
}
.arena-chip3d {
    min-width: 6.5rem;
    padding: .65rem .9rem;
    border-radius: 1rem;
    background: #fff;
    border: 2px solid rgba(18, 52, 91, 0.1);
    box-shadow: 0 5px 0 rgba(18, 52, 91, 0.1);
    text-align: center;
}
.dark .arena-chip3d {
    background: #0f172a;
    border-color: #334155;
    box-shadow: 0 5px 0 rgba(0,0,0,.35);
}
.arena-chip3d strong {
    display: block;
    font-size: 1.35rem;
    font-weight: 800;
    color: var(--arena-teal);
    line-height: 1.1;
}
.arena-chip3d span {
    font-size: .65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: var(--arena-muted);
}
.arena-chip3d-amber strong { color: #d48900; }
.arena-chip3d-sky strong { color: #2276d2; }

.arena-lobby-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .65rem;
    margin-top: 1.25rem;
}
.arena-play-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    min-height: 3.15rem;
    padding: .75rem 1.5rem;
    border-radius: 1rem;
    border: none;
    font-weight: 800;
    font-size: .95rem;
    color: #fff;
    background: linear-gradient(180deg, #5cff8f 0%, #16e065 40%, #00c853 100%);
    box-shadow:
        inset 0 2px 0 rgba(255,255,255,.45),
        0 6px 0 #008f38,
        0 12px 28px rgba(0, 200, 83, 0.5);
    transition: transform .1s ease, box-shadow .1s ease, filter .15s ease, background .15s ease, opacity .15s ease;
    text-decoration: none;
    cursor: pointer;
    text-shadow: 0 1px 0 rgba(0, 80, 30, 0.25);
}
.arena-play-btn:hover:not(:disabled) {
    filter: brightness(1.08) saturate(1.1);
    transform: translateY(-1px);
    box-shadow:
        inset 0 2px 0 rgba(255,255,255,.5),
        0 7px 0 #008f38,
        0 16px 32px rgba(0, 200, 83, 0.55);
}
.arena-play-btn:active:not(:disabled) {
    transform: translateY(4px);
    filter: brightness(1);
    box-shadow:
        inset 0 2px 0 rgba(255,255,255,.25),
        0 2px 0 #008f38,
        0 6px 12px rgba(0, 200, 83, 0.3);
}
.arena-play-btn:disabled,
.arena-play-btn[disabled] {
    cursor: not-allowed;
    color: #94a3b8;
    text-shadow: none;
    background: linear-gradient(180deg, #e2e8f0 0%, #cbd5e1 100%);
    box-shadow:
        inset 0 2px 0 rgba(255,255,255,.5),
        0 5px 0 #94a3b8;
    filter: none;
    opacity: 1;
}
.arena-play-btn-amber {
    background: linear-gradient(180deg, #ffc44d 0%, #ffb020 55%, #e09410 100%);
    color: #3b2700;
    box-shadow:
        inset 0 2px 0 rgba(255,255,255,.4),
        0 6px 0 #c07a00,
        0 12px 24px rgba(224, 148, 16, 0.35);
}
.arena-play-btn-amber:hover {
    box-shadow:
        inset 0 2px 0 rgba(255,255,255,.4),
        0 7px 0 #c07a00,
        0 14px 28px rgba(224, 148, 16, 0.42);
}
.arena-play-btn-amber:active {
    box-shadow:
        inset 0 2px 0 rgba(255,255,255,.25),
        0 2px 0 #c07a00,
        0 6px 12px rgba(224, 148, 16, 0.25);
}
.arena-play-btn-ghost {
    background: rgba(255,255,255,.85);
    color: var(--arena-navy);
    box-shadow:
        inset 0 2px 0 rgba(255,255,255,.5),
        0 6px 0 rgba(18, 52, 91, 0.12),
        0 12px 20px rgba(18, 52, 91, 0.1);
}
.dark .arena-play-btn-ghost {
    background: rgba(15, 23, 42, .9);
    color: #e2e8f0;
    box-shadow: 0 6px 0 rgba(0,0,0,.35);
}

.arena-world-portals {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .85rem;
    margin: .25rem 0 1.25rem;
}
@media (max-width: 639px) {
    .arena-world-portals { grid-template-columns: 1fr; }
}
.arena-portal {
    display: grid;
    grid-template-columns: 5.5rem 1fr;
    gap: .85rem;
    align-items: center;
    text-align: left;
    padding: .65rem;
    border-radius: 1.25rem;
    border: 3px solid rgba(18, 52, 91, 0.1);
    background: rgba(255,255,255,.92);
    box-shadow: 0 7px 0 rgba(18, 52, 91, 0.1);
    cursor: pointer;
    transition: transform .12s ease, box-shadow .12s ease, border-color .15s ease;
    position: relative;
}
.dark .arena-portal {
    background: rgba(15, 23, 42, .92);
    border-color: #334155;
    box-shadow: 0 7px 0 rgba(0,0,0,.35);
}
.arena-portal:hover { transform: translateY(-2px); }
.arena-portal:active { transform: translateY(3px); box-shadow: 0 3px 0 rgba(18, 52, 91, 0.1); }
.arena-portal.active {
    border-color: var(--arena-teal);
    box-shadow: 0 7px 0 rgba(0, 169, 157, 0.35);
}
.arena-portal-thumb {
    width: 5.5rem;
    height: 5.5rem;
    border-radius: 1rem;
    display: grid;
    place-items: center;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: inset 0 2px 0 rgba(255,255,255,.3);
}
.arena-portal-thumb-kuis {
    background: linear-gradient(145deg, #00c2b2, #0b3d6e);
}
.arena-portal-thumb-misi {
    background: linear-gradient(145deg, #ffb020, #e85d75);
}
.arena-portal-shine {
    position: absolute;
    inset: 0;
    background: linear-gradient(115deg, transparent 40%, rgba(255,255,255,.35) 50%, transparent 60%);
    animation: arena-shine-sweep 3.5s ease-in-out infinite;
}
@keyframes arena-shine-sweep {
    0%, 70%, 100% { transform: translateX(-120%); }
    85% { transform: translateX(120%); }
}
.arena-portal-label {
    display: block;
    font-size: .65rem;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--arena-muted);
}
.arena-portal-title {
    display: block;
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--arena-ink);
    line-height: 1.15;
}
.dark .arena-portal-title { color: #f1f5f9; }
.arena-portal-meta {
    display: block;
    margin-top: .2rem;
    font-size: .72rem;
    font-weight: 600;
    color: var(--arena-muted);
}
.arena-portal-join {
    position: absolute;
    top: .55rem;
    right: .65rem;
    padding: .15rem .45rem;
    border-radius: .4rem;
    background: var(--arena-play);
    color: #fff;
    font-size: .58rem;
    font-weight: 800;
    letter-spacing: .06em;
    box-shadow: 0 2px 0 #00963e;
}

.arena-discover { position: relative; z-index: 2; }
.arena-discover-head {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: .75rem;
    flex-wrap: wrap;
}
.arena-discover-title {
    margin: 0;
    font-size: clamp(1.35rem, 3vw, 1.75rem);
    font-weight: 800;
    color: var(--arena-ink);
}
.dark .arena-discover-title { color: #f1f5f9; }
.arena-mini-cta {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    min-height: 2.4rem;
    padding: .45rem .9rem;
    border-radius: .85rem;
    background: #fff;
    border: 2px solid rgba(18, 52, 91, 0.1);
    color: var(--arena-teal);
    font-size: .8rem;
    font-weight: 800;
    box-shadow: 0 4px 0 rgba(18, 52, 91, 0.08);
    text-decoration: none;
}
.dark .arena-mini-cta {
    background: #0f172a;
    border-color: #334155;
    box-shadow: 0 4px 0 rgba(0,0,0,.3);
}
.arena-mini-cta-ghost { color: var(--arena-muted); }
.arena-lobby-panel {
    border-width: 2px;
    border-radius: 1.25rem;
    box-shadow: 0 7px 0 rgba(18, 52, 91, 0.08);
}

.arena-xp-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}
@media (min-width: 1024px) {
    .arena-xp-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.15rem; }
}
@media (max-width: 639px) {
    .arena-xp-grid { grid-template-columns: 1fr; }
}
.arena-xp-card {
    display: flex;
    flex-direction: column;
    border-radius: 1.15rem;
    overflow: hidden;
    background: #fff;
    border: 3px solid rgba(18, 52, 91, 0.08);
    box-shadow: 0 8px 0 rgba(18, 52, 91, 0.1);
    text-decoration: none;
    color: inherit;
    transition: transform .14s ease, box-shadow .14s ease;
}
.dark .arena-xp-card {
    background: #0f172a;
    border-color: #334155;
    box-shadow: 0 8px 0 rgba(0,0,0,.35);
}
.arena-xp-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 0 rgba(18, 52, 91, 0.12);
}
.arena-xp-card:active {
    transform: translateY(2px);
    box-shadow: 0 4px 0 rgba(18, 52, 91, 0.1);
}
.arena-xp-thumb {
    position: relative;
    aspect-ratio: 16 / 10;
    display: grid;
    place-items: center;
    color: #fff;
    background:
        radial-gradient(circle at 80% 20%, rgba(255,255,255,.3), transparent 40%),
        linear-gradient(145deg, var(--art-a, var(--arena-teal)), var(--art-b, var(--arena-navy)));
    overflow: hidden;
}
.arena-xp-blocks {
    position: absolute;
    inset: 0;
    opacity: .35;
    background:
        linear-gradient(90deg, transparent 46%, rgba(255,255,255,.2) 46% 54%, transparent 54%),
        linear-gradient(transparent 46%, rgba(255,255,255,.15) 46% 54%, transparent 54%);
    background-size: 28px 28px;
}
.arena-xp-play {
    position: absolute;
    right: .7rem;
    bottom: .7rem;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: .85rem;
    background: var(--arena-play);
    color: #fff;
    display: grid;
    place-items: center;
    box-shadow: 0 4px 0 #00963e;
    transition: transform .12s ease;
}
.arena-xp-card:hover .arena-xp-play { transform: scale(1.08); }
.arena-xp-status {
    position: absolute;
    top: .65rem;
    left: .65rem;
    padding: .2rem .5rem;
    border-radius: .45rem;
    background: rgba(0,0,0,.35);
    backdrop-filter: blur(4px);
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: #fff;
}
.arena-xp-status.is-closed { background: rgba(232, 93, 117, 0.75); }
.arena-xp-info {
    display: grid;
    gap: .35rem;
    padding: .85rem .95rem 1rem;
    flex: 1;
}
.arena-xp-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 800;
    line-height: 1.25;
    color: var(--arena-ink);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.dark .arena-xp-title { color: #f1f5f9; }
.arena-xp-meta {
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: .3rem;
    font-size: .75rem;
    font-weight: 700;
    color: var(--arena-muted);
}
.arena-xp-cta {
    margin-top: .35rem;
    font-size: .78rem;
    font-weight: 800;
    color: var(--arena-teal);
}
.arena-xp-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 2.5rem 1.25rem;
    border-radius: 1.25rem;
    background: rgba(255,255,255,.9);
    border: 3px dashed rgba(18, 52, 91, 0.15);
}
.dark .arena-xp-empty {
    background: rgba(15, 23, 42, .9);
    border-color: #334155;
}
.arena-xp-empty-ico {
    width: 4rem;
    height: 4rem;
    margin: 0 auto .85rem;
    border-radius: 1.1rem;
    display: grid;
    place-items: center;
    background: #eaf8f6;
    color: var(--arena-teal);
    box-shadow: 0 5px 0 rgba(0, 169, 157, 0.2);
}

.arena-hero {
    background:
        radial-gradient(ellipse 55% 70% at 92% 8%, rgba(245, 165, 36, 0.42), transparent 52%),
        radial-gradient(ellipse 50% 60% at 6% 90%, rgba(0, 169, 157, 0.48), transparent 48%),
        radial-gradient(circle at 50% 120%, rgba(232, 93, 117, 0.18), transparent 45%),
        linear-gradient(148deg, #0a223d 0%, #12345b 42%, #0c3d4a 100%);
    color: #f8fafc;
    border-radius: 1.35rem;
    overflow: hidden;
    position: relative;
    box-shadow: var(--arena-shadow-lg);
    isolation: isolate;
}
.arena-hero-stars {
    position: absolute;
    inset: 0;
    pointer-events: none;
    background-image:
        radial-gradient(1.5px 1.5px at 12% 22%, rgba(255,255,255,.55), transparent),
        radial-gradient(1px 1px at 28% 68%, rgba(255,255,255,.4), transparent),
        radial-gradient(1.5px 1.5px at 48% 18%, rgba(255,255,255,.35), transparent),
        radial-gradient(1px 1px at 66% 42%, rgba(255,255,255,.5), transparent),
        radial-gradient(1.5px 1.5px at 78% 72%, rgba(255,255,255,.3), transparent),
        radial-gradient(1px 1px at 90% 28%, rgba(255,255,255,.45), transparent);
    opacity: .7;
    animation: arena-twinkle 4.5s ease-in-out infinite;
}
@keyframes arena-twinkle {
    0%, 100% { opacity: .45; }
    50% { opacity: .85; }
}
.arena-hero::before {
    content: '';
    position: absolute;
    width: 280px;
    height: 280px;
    right: -70px;
    top: -80px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,.1);
    pointer-events: none;
}
.arena-hero::after {
    content: '';
    position: absolute;
    width: 180px;
    height: 180px;
    right: 36px;
    bottom: -50px;
    border-radius: 50%;
    border: 2px solid rgba(0, 169, 157, 0.28);
    pointer-events: none;
}

.arena-intro {
    display: grid;
    grid-template-columns: minmax(0, 1.3fr) minmax(200px, 0.7fr);
    gap: 1.75rem;
    align-items: center;
}
@media (max-width: 639px) {
    .arena-intro { grid-template-columns: 1fr; }
    .arena-planet { display: none; }
}

.arena-eyebrow {
    margin: 0 0 .45rem;
    color: var(--arena-amber);
    font-size: .72rem;
    font-weight: 800;
    letter-spacing: .1em;
    text-transform: uppercase;
}

.arena-title {
    font-size: clamp(2rem, 5vw, 2.75rem);
    font-weight: 900;
    letter-spacing: -0.03em;
    line-height: 1.05;
    text-shadow: 0 8px 28px rgba(0,0,0,.25);
}

.arena-planet {
    position: relative;
    width: min(100%, 220px);
    aspect-ratio: 1;
    margin-inline: auto;
    display: grid;
    place-items: center;
}
.arena-planet-core {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    background: radial-gradient(circle at 32% 28%, #fff 0 8%, var(--arena-amber) 9% 40%, var(--arena-rose) 41% 100%);
    color: #fff;
    display: grid;
    place-items: center;
    font-size: 1.85rem;
    font-weight: 900;
    box-shadow:
        0 0 0 10px rgba(245, 165, 36, 0.12),
        0 16px 32px rgba(232, 93, 117, 0.35);
    z-index: 1;
    animation: arena-float 3.6s ease-in-out infinite;
}
.arena-orbit {
    position: absolute;
    border: 2px solid rgba(255,255,255,.16);
    border-radius: 50%;
}
.arena-orbit-a { width: 78%; height: 78%; animation: arena-spin 16s linear infinite; }
.arena-orbit-b {
    width: 98%; height: 44%;
    border-color: rgba(0, 169, 157, 0.42);
    animation: arena-spin-rev 20s linear infinite;
}
.arena-orbit-dot {
    position: absolute;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--arena-teal);
    box-shadow: 0 0 12px rgba(0, 169, 157, .8);
    top: 8%;
    left: 50%;
    transform: translateX(-50%);
}
@keyframes arena-spin { to { transform: rotate(360deg); } }
@keyframes arena-spin-rev {
    from { transform: rotate(-18deg); }
    to { transform: rotate(-378deg); }
}
@keyframes arena-float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

.arena-mode-tabs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .4rem;
    padding: .4rem;
    border-radius: 1.1rem;
    background: rgba(255,255,255,.92);
    border: 1px solid var(--arena-line);
    box-shadow: var(--arena-shadow);
    backdrop-filter: blur(8px);
}
.dark .arena-mode-tabs {
    background: rgba(15, 23, 42, .92);
    border-color: #334155;
}
.arena-mode-tab {
    min-height: 3.35rem;
    border-radius: .85rem;
    border: 1px solid transparent;
    background: transparent;
    color: var(--arena-navy);
    font-weight: 800;
    font-size: .9rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .55rem;
    cursor: pointer;
    transition: transform .15s ease, background .18s ease, color .18s ease, box-shadow .18s ease;
}
.dark .arena-mode-tab { color: #e2e8f0; }
.arena-mode-tab .arena-mode-ico {
    width: 2rem;
    height: 2rem;
    border-radius: .65rem;
    display: grid;
    place-items: center;
    background: #eef2f7;
    color: var(--arena-navy);
    transition: background .18s ease, color .18s ease;
}
.dark .arena-mode-tab .arena-mode-ico {
    background: #1e293b;
    color: #cbd5e1;
}
.arena-mode-tab.active {
    background: linear-gradient(135deg, #00a99d, #0d8f86);
    color: #fff;
    box-shadow: 0 10px 24px rgba(0, 169, 157, 0.35);
    transform: translateY(-1px);
}
.arena-mode-tab.active .arena-mode-ico {
    background: rgba(255,255,255,.22);
    color: #fff;
}
.arena-mode-tab:not(.active):hover {
    background: #f3f7f7;
}
.dark .arena-mode-tab:not(.active):hover {
    background: #1e293b;
}

.arena-panel {
    border: 1px solid var(--arena-line);
    border-radius: 1.1rem;
    background: #fff;
    box-shadow: var(--arena-shadow);
    padding: 1.25rem 1.35rem;
}
.dark .arena-panel {
    background: #0f172a;
    border-color: #334155;
    box-shadow: none;
}

.arena-mission-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.1rem;
}
@media (min-width: 1024px) {
    .arena-mission-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
@media (max-width: 639px) {
    .arena-mission-grid { grid-template-columns: 1fr; }
}

.arena-mission-card {
    min-height: 300px;
    border: 1px solid transparent;
    border-radius: 1.15rem;
    padding: 0;
    background: #fff;
    display: grid;
    grid-template-rows: auto 1fr;
    overflow: hidden;
    transition: transform .22s cubic-bezier(.2,.8,.2,1), box-shadow .22s ease;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 8px 24px rgba(18, 52, 91, 0.08);
}
.dark .arena-mission-card {
    background: #0f172a;
    box-shadow: none;
    border-color: #334155;
}
.arena-mission-card:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: var(--arena-shadow-lg);
}
.arena-mission-card-body {
    display: grid;
    gap: .7rem;
    padding: 1rem 1.1rem 1.15rem;
    align-content: start;
    min-height: 0;
}

/* Katalog Misi — grid & kartu sejajar */
.arena-catalog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.1rem;
}
.arena-catalog-empty { grid-column: 1 / -1; }
.arena-catalog-card {
    height: 100%;
    min-height: 340px;
}
.arena-catalog-body {
    grid-template-rows: auto auto 1fr auto auto;
    gap: .55rem;
    height: 100%;
}
.arena-catalog-pills {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    align-items: center;
    min-height: 1.7rem;
}
.arena-catalog-pills .arena-pill {
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
}
.arena-catalog-title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 900;
    line-height: 1.3;
    color: #172033;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.6em;
}
.dark .arena-catalog-title { color: #f1f5f9; }
.arena-catalog-summary {
    margin: 0;
    font-size: .84rem;
    line-height: 1.45;
    color: #667085;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.5em;
}
.dark .arena-catalog-summary { color: #94a3b8; }
.arena-catalog-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .35rem;
    font-size: .72rem;
    font-weight: 700;
    color: #94a3b8;
}
.arena-catalog-cta {
    margin-top: .15rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: .78rem;
    font-weight: 800;
    color: var(--arena-teal);
    padding-top: .55rem;
    border-top: 1px dashed var(--arena-line);
}

.arena-card-art {
    height: 124px;
    border-radius: 0;
    background:
        radial-gradient(circle at 80% 20%, rgba(255,255,255,.28), transparent 40%),
        linear-gradient(135deg, var(--art-a, var(--arena-teal)), var(--art-b, var(--arena-navy)));
    position: relative;
    overflow: hidden;
    display: grid;
    place-items: center;
    color: #fff;
}
.arena-card-art::before,
.arena-card-art::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.22);
}
.arena-card-art::before {
    width: 110px; height: 110px;
    right: -18px; top: -28px;
}
.arena-card-art::after {
    width: 48px; height: 48px;
    left: 16px; bottom: -10px;
}
.arena-card-art > * { position: relative; z-index: 1; }
.arena-card-shine {
    position: absolute;
    inset: 0;
    background: linear-gradient(115deg, transparent 35%, rgba(255,255,255,.28) 48%, transparent 62%);
    transform: translateX(-120%);
    transition: transform .55s ease;
}
.arena-mission-card:hover .arena-card-shine { transform: translateX(120%); }
.arena-card-play {
    position: absolute;
    right: .85rem;
    bottom: .85rem;
    z-index: 2;
    width: 2.4rem;
    height: 2.4rem;
    border-radius: 999px;
    background: #fff;
    color: var(--arena-navy);
    display: grid;
    place-items: center;
    box-shadow: 0 8px 18px rgba(0,0,0,.18);
    transition: transform .18s ease;
}
.arena-mission-card:hover .arena-card-play { transform: scale(1.08); }

.arena-pill {
    border-radius: 999px;
    padding: .32rem .6rem;
    color: #334155;
    background: #eef2f7;
    font-size: .65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .03em;
}
.dark .arena-pill {
    background: #1e293b;
    color: #94a3b8;
}
.arena-pill-teal { background: #eaf8f6; color: #007a72; }
.arena-pill-amber { background: #fff4e0; color: #9a6700; }
.arena-pill-rose { background: #fde8ec; color: #b42345; }
.arena-pill-sd { background: #e8f5e9; color: #1b7a3a; }
.arena-pill-smp { background: #e3f2fd; color: #1565c0; }
.arena-pill-sma { background: #f3e8ff; color: #7c3aed; }
.arena-pill-umum { background: #eef2f7; color: #475569; }
.dark .arena-pill-teal { background: rgba(0,169,157,.2); color: #5eead4; }
.dark .arena-pill-amber { background: rgba(245,165,36,.2); color: #fbbf24; }
.dark .arena-pill-rose { background: rgba(232,93,117,.2); color: #fda4af; }
.dark .arena-pill-sd { background: rgba(34,197,94,.18); color: #86efac; }
.dark .arena-pill-smp { background: rgba(59,130,246,.2); color: #93c5fd; }
.dark .arena-pill-sma { background: rgba(168,85,247,.2); color: #d8b4fe; }
.dark .arena-pill-umum { background: #1e293b; color: #94a3b8; }

.arena-jenjang-filters { display: flex; flex-wrap: wrap; gap: .4rem; }
.arena-jenjang-chip {
    border: 1px solid var(--arena-line);
    background: #fff;
    color: var(--arena-navy);
    border-radius: 999px;
    padding: .4rem .85rem;
    font-size: .75rem;
    font-weight: 800;
    cursor: pointer;
    transition: background .15s ease, color .15s ease, border-color .15s ease, transform .15s ease;
}
.dark .arena-jenjang-chip { background: #0f172a; color: #e2e8f0; border-color: #334155; }
.arena-jenjang-chip.active {
    background: var(--arena-navy);
    color: #fff;
    border-color: var(--arena-navy);
    transform: translateY(-1px);
}
.arena-jenjang-chip.arena-jenjang-sd.active { background: #1b7a3a; border-color: #1b7a3a; }
.arena-jenjang-chip.arena-jenjang-smp.active { background: #1565c0; border-color: #1565c0; }
.arena-jenjang-chip.arena-jenjang-sma.active { background: #7c3aed; border-color: #7c3aed; }
.arena-jenjang-chip.arena-jenjang-tren.active {
    background: linear-gradient(135deg, #f59e0b, #ea580c);
    border-color: #ea580c;
    color: #fff;
}
.arena-pill-tren {
    background: linear-gradient(135deg, #fff7ed, #ffedd5);
    color: #c2410c;
    border: 1px solid #fdba74;
}
.dark .arena-pill-tren {
    background: rgba(234, 88, 12, 0.22);
    color: #fdba74;
    border-color: rgba(251, 146, 60, 0.35);
}
.arena-tren-card { background: linear-gradient(180deg, #fffbeb, #f8fafc); }
.dark .arena-tren-card { background: linear-gradient(180deg, #1c1917, #0f172a); }
.arena-jenjang-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: .85rem;
}
@media (max-width: 900px) {
    .arena-jenjang-grid { grid-template-columns: 1fr; }
}
.arena-jenjang-card {
    border: 1px solid var(--arena-line);
    border-radius: .9rem;
    padding: .9rem 1rem;
    background: #f8fafc;
}
.dark .arena-jenjang-card { background: #0f172a; border-color: #334155; }
.arena-jenjang-card.arena-jenjang-sd { border-top: 3px solid #1b7a3a; }
.arena-jenjang-card.arena-jenjang-smp { border-top: 3px solid #1565c0; }
.arena-jenjang-card.arena-jenjang-sma { border-top: 3px solid #7c3aed; }
.arena-jenjang-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    gap: .7rem;
}
.arena-jenjang-list li {
    display: grid;
    gap: .15rem;
    padding-bottom: .65rem;
    border-bottom: 1px dashed var(--arena-line);
}
.arena-jenjang-list li:last-child { border-bottom: 0; padding-bottom: 0; }
.arena-jenjang-list strong {
    font-size: .84rem;
    color: var(--arena-ink);
    line-height: 1.3;
}
.dark .arena-jenjang-list strong { color: #e2e8f0; }
.arena-jenjang-list span {
    font-size: .7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: var(--arena-teal);
}
.arena-jenjang-list em {
    font-style: normal;
    font-size: .75rem;
    color: var(--arena-muted);
    line-height: 1.35;
}

.arena-stat-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: .65rem;
}
@media (min-width: 640px) {
    .arena-stat-row { grid-template-columns: repeat(4, minmax(0, 1fr)); }
}
.arena-stat {
    border-radius: .85rem;
    border: 1px solid rgba(255,255,255,.14);
    background: rgba(255,255,255,.08);
    padding: .8rem .95rem;
    backdrop-filter: blur(4px);
    transition: background .15s ease, transform .15s ease;
}
.arena-stat:hover {
    background: rgba(255,255,255,.14);
    transform: translateY(-1px);
}
.arena-stat strong {
    display: block;
    font-size: 1.45rem;
    font-weight: 900;
    line-height: 1.1;
}
.arena-stat span {
    font-size: .66rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    opacity: .75;
}

.arena-section-head h2 {
    letter-spacing: -0.02em;
}
.arena-cta-row { display: flex; flex-wrap: wrap; gap: .55rem; }

@keyframes arena-pop {
    0% { transform: scale(.92); opacity: 0; }
    70% { transform: scale(1.03); }
    100% { transform: scale(1); opacity: 1; }
}
@keyframes arena-slide-up {
    from { transform: translateY(18px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
@keyframes arena-pulse-bar {
    0%, 100% { filter: brightness(1); }
    50% { filter: brightness(1.15); }
}
@keyframes arena-shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}
@keyframes arena-score-burst {
    0% { transform: scale(.6); opacity: 0; }
    40% { transform: scale(1.12); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
}

.arena-anim-in { animation: arena-slide-up .38s ease-out both; }
.arena-anim-pop { animation: arena-pop .42s cubic-bezier(.2,1.2,.4,1) both; }
.arena-feedback-ok { animation: arena-score-burst .45s ease-out; color: var(--arena-ok); }
.arena-feedback-bad { animation: arena-shake .35s ease-out; color: var(--arena-bad); }

.arena-progress {
    height: .55rem;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
    overflow: hidden;
}
.arena-progress > span {
    display: block;
    height: 100%;
    border-radius: inherit;
    background: linear-gradient(90deg, var(--cp), color-mix(in srgb, var(--cp) 40%, #fbbf24));
    transition: width .45s cubic-bezier(.2,.8,.2,1);
    animation: arena-pulse-bar 2.2s ease-in-out infinite;
}

.arena-opt {
    display: flex;
    align-items: center;
    gap: .85rem;
    width: 100%;
    text-align: left;
    min-height: 3.4rem;
    padding: .9rem 1rem;
    border-radius: 1rem;
    border: 2px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.06);
    color: #f1f5f9;
    font-weight: 650;
    font-size: .95rem;
    transition: transform .15s ease, border-color .15s ease, background .15s ease, box-shadow .15s ease;
    cursor: pointer;
}
.arena-opt:hover:not(:disabled) {
    transform: translateY(-2px);
    border-color: color-mix(in srgb, var(--cp) 70%, white);
    background: rgba(255,255,255,.1);
}
.arena-opt:active:not(:disabled) { transform: scale(.98); }
.arena-opt.is-selected {
    border-color: transparent;
    background: var(--cp);
    color: #fff;
    box-shadow: 0 8px 24px color-mix(in srgb, var(--cp) 35%, transparent);
}
.arena-opt.is-correct {
    border-color: var(--arena-ok);
    background: color-mix(in srgb, var(--arena-ok) 28%, transparent);
}
.arena-opt-letter {
    flex-shrink: 0;
    width: 2.1rem;
    height: 2.1rem;
    border-radius: .7rem;
    display: grid;
    place-items: center;
    font-weight: 900;
    font-size: .85rem;
    background: rgba(255,255,255,.14);
    letter-spacing: .02em;
}
.arena-opt.is-selected .arena-opt-letter {
    background: rgba(255,255,255,.25);
}

.arena-play-shell {
    background:
        radial-gradient(ellipse 90% 70% at 50% -10%, color-mix(in srgb, var(--cp) 35%, transparent), transparent 55%),
        linear-gradient(180deg, #0c1a24 0%, #101f2a 40%, #0b1520 100%);
    border-radius: 1.5rem;
    color: #f8fafc;
    padding: 1.25rem;
    min-height: min(70vh, 36rem);
}
@media (min-width: 640px) {
    .arena-play-shell { padding: 1.75rem; }
}

.arena-chip {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .3rem .7rem;
    border-radius: .5rem;
    font-size: .7rem;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
    background: rgba(245, 165, 36, 0.22);
    color: #ffe8b8;
}

.arena-cta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    min-height: 2.85rem;
    padding: .7rem 1.25rem;
    border-radius: .85rem;
    font-weight: 800;
    font-size: .875rem;
    color: #fff;
    background: linear-gradient(135deg, #00a99d, #0d8f86);
    border: none;
    box-shadow: 0 8px 20px rgba(0, 169, 157, 0.28);
    transition: transform .15s ease, filter .15s ease, box-shadow .15s ease;
}
.arena-cta:hover { transform: translateY(-2px); filter: brightness(1.05); box-shadow: 0 12px 28px rgba(0, 169, 157, 0.38); }
.arena-cta-amber {
    background: linear-gradient(135deg, #f5a524, #e09410);
    color: #1b2434;
    box-shadow: 0 8px 20px rgba(245, 165, 36, 0.28);
}
.arena-cta-amber:hover { filter: brightness(1.05); box-shadow: 0 12px 28px rgba(245, 165, 36, 0.38); }
.arena-cta-ghost {
    background: rgba(255,255,255,.1);
    color: #e2e8f0;
    border: 1.5px solid rgba(255,255,255,.22);
    box-shadow: none;
}
.arena-cta-ghost:hover { background: rgba(255,255,255,.18); filter: none; box-shadow: none; }

.arena-card-game {
    border-radius: .75rem;
    border: 1px solid var(--arena-line);
    background: #fff;
    box-shadow: 0 1px 0 rgba(18, 52, 91, 0.04);
    transition: transform .18s ease, box-shadow .18s ease;
}
.dark .arena-card-game {
    background: #0f172a;
    border-color: #334155;
}
.arena-card-game:hover {
    transform: translateY(-3px);
    box-shadow: var(--arena-shadow);
}

.arena-rank {
    width: 1.75rem;
    height: 1.75rem;
    border-radius: .55rem;
    display: grid;
    place-items: center;
    font-weight: 900;
    font-size: .75rem;
    background: #e2e8f0;
    color: #475569;
}
.arena-rank-1 { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #422006; }
.arena-rank-2 { background: linear-gradient(135deg, #cbd5e1, #94a3b8); color: #0f172a; }
.arena-rank-3 { background: linear-gradient(135deg, #fdba74, #ea580c); color: #431407; }

.arena-score-orb {
    width: 8.5rem;
    height: 8.5rem;
    margin: 0 auto;
    border-radius: 50%;
    display: grid;
    place-items: center;
    background:
        radial-gradient(circle at 30% 25%, rgba(255,255,255,.35), transparent 45%),
        linear-gradient(145deg, var(--cp), color-mix(in srgb, var(--cp) 45%, #0c1a24));
    color: #fff;
    box-shadow: 0 16px 40px color-mix(in srgb, var(--cp) 40%, transparent);
    animation: arena-score-burst .55s ease-out;
}

.arena-fs-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    min-height: 2.5rem;
    padding: .45rem .75rem;
    border-radius: .75rem;
    border: 1px solid rgba(255,255,255,.2);
    background: rgba(255,255,255,.1);
    color: #f1f5f9;
    font-size: .75rem;
    font-weight: 800;
    cursor: pointer;
    transition: background .15s ease, border-color .15s ease;
}
.arena-fs-btn:hover {
    background: rgba(255,255,255,.18);
    border-color: rgba(255,255,255,.35);
}
.arena-fs-btn svg { width: 1rem; height: 1rem; flex-shrink: 0; }

.arena-play-shell:fullscreen,
.arena-play-shell:-webkit-full-screen,
.arena-live-stage:fullscreen,
.arena-live-stage:-webkit-full-screen,
.arena-play-shell.arena-is-fullscreen,
.arena-live-stage.arena-is-fullscreen {
    border-radius: 0 !important;
    width: 100%;
    height: 100%;
    min-height: 100%;
    max-width: none;
    overflow: auto;
    padding: 1.5rem clamp(1rem, 4vw, 3rem);
    box-sizing: border-box;
}
.arena-play-shell.arena-is-fullscreen,
.arena-live-stage.arena-is-fullscreen {
    position: fixed;
    inset: 0;
    z-index: 9999;
}
.arena-play-shell:fullscreen .arena-fs-stack,
.arena-play-shell.arena-is-fullscreen .arena-fs-stack,
.arena-live-stage:fullscreen .arena-fs-stack,
.arena-live-stage.arena-is-fullscreen .arena-fs-stack {
    max-width: 42rem;
    margin-inline: auto;
}
</style>
