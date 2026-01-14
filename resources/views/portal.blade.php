<!DOCTYPE html>
<html lang="fr" class="h-full bg-black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G R O U P E | Singularity OS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="favicon.png">

    <style>
        @media (max-width: 768px){
            body{
                overflow:visible!important;
            }
        }
        body { 
            font-family: 'Space Grotesk', sans-serif; 
            background-color: #000;
            overflow: hidden; /* Immersion totale */
        }
        
        /* --- UTILITAIRES --- */
        .js-hidden { opacity: 0; }
        
        /* --- EFFETS VISUELS AVANCÉS --- */
        #gl-canvas {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1; pointer-events: none;
        }

        /* Scanlines pour effet écran CRT subtil */
        .scanlines {
            position: fixed; inset: 0; pointer-events: none; z-index: 50;
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1));
            background-size: 100% 4px;
            opacity: 0.3;
        }

        /* --- CORRECTION VISIBILITÉ --- */
        .glass-panel {
            background: rgba(20, 30, 50, 0.85); 
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* --- NOUVEAU : STYLE DES ICONES PRO --- */
        .icon-box {
            display: flex; align-items: center; justify-content: center;
            width: 3.5rem; height: 3.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .glow-blue {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.1);
        }
        .group:hover .glow-blue {
            background: rgba(59, 130, 246, 0.2);
            box-shadow: 0 0 25px rgba(59, 130, 246, 0.4);
            transform: scale(1.05);
        }

        .glow-emerald {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.1);
        }
        .group:hover .glow-emerald {
            background: rgba(16, 185, 129, 0.2);
            box-shadow: 0 0 25px rgba(16, 185, 129, 0.4);
            transform: scale(1.05);
        }

        .glow-orange {
            background: rgba(249, 115, 22, 0.1);
            border: 1px solid rgba(249, 115, 22, 0.2);
            box-shadow: 0 0 15px rgba(249, 115, 22, 0.1);
        }
        .group:hover .glow-orange {
            background: rgba(249, 115, 22, 0.2);
            box-shadow: 0 0 25px rgba(249, 115, 22, 0.4);
            transform: scale(1.05);
        }

        .glow-gray {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Effet de Glitch text au survol */
        .glitch-hover:hover {
            animation: glitch-skew 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94) both infinite;
            color: #fff;
        }
        @keyframes glitch-skew {
            0% { transform: skew(0deg); }
            20% { transform: skew(-2deg); }
            40% { transform: skew(2deg); }
            60% { transform: skew(-1deg); }
            80% { transform: skew(1deg); }
            100% { transform: skew(0deg); }
        }

        /* Cursor personnalisé */
        .custom-cursor {
            width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.8);
            border-radius: 50%;
            position: fixed; pointer-events: none; z-index: 9999;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s, background-color 0.3s;
            mix-blend-mode: difference;
        }
        .custom-cursor.hovered {
            width: 50px; height: 50px;
            background-color: rgba(255,255,255,0.2);
            border-color: transparent;
            backdrop-filter: blur(2px);
        }

        /* Layout Grid personnalisé */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            width: 100%;
            max-width: 1400px;
        }
    </style>
</head>

<body class="text-white selection:bg-white selection:text-black">

    <canvas id="gl-canvas"></canvas>
    
    <div class="scanlines"></div>

    <div id="cursor" class="custom-cursor hidden md:block"></div>

    <div id="app" class="relative z-10 min-h-screen flex flex-col p-6 md:p-12 js-hidden">
        
        <header class="flex justify-between items-end border-b border-white/10 pb-6 mb-12">
            <div>
                <p class="text-xs font-mono text-blue-400 mb-1 scramble-text">SYSTEM READY // V4.0</p>
                <h1 class="text-4xl md:text-6xl font-bold tracking-tighter">
                    GROUPE <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">AUSTRALE</span>
                </h1>
            </div>
            <div class="hidden md:block text-right">
                <p class="text-xs text-gray-500 font-mono scramble-text" id="clock">--:--:--</p>
                <p class="text-sm font-medium text-gray-400">Hub Opérationnel</p>
            </div>
        </header>

        <main class="flex-grow flex items-center justify-center">
            <div class="cards-grid perspective-container">

                <div class="magnetic-wrap group">
                    <div class="glass-panel rounded-2xl p-8 h-full relative overflow-hidden transition-all duration-500 hover:border-blue-500/50">
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 opacity-0 group-hover:opacity-20 blur-xl transition-opacity duration-500"></div>
                        
                        <div class="relative z-10 flex flex-col h-full">
                            <div class="flex items-center justify-between mb-8">
                                <div class="icon-box glow-blue">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <div class="h-px flex-grow mx-4 bg-white/10 group-hover:bg-blue-500/50 transition-colors"></div>
                                <span class="text-xs font-mono text-blue-400">ID: AUST-01</span>
                            </div>
                            <img src="{{asset('img/1.png')}}" width="100" alt="" />
                            <h2 class="text-3xl font-bold mb-2 group-hover:translate-x-2 transition-transform duration-300">Australe</h2>
                            <p class="text-gray-400 text-sm mb-8 leading-relaxed">Infrastructure pédagogique et gestion des stocks matériels.</p>
                            
                            <div class="mt-auto space-y-3">
                                <a href="https://inventoria.on-forge.com" class="magnetic-btn block w-full py-4 bg-white/5 hover:bg-blue-600 hover:text-white border border-white/10 text-center rounded-lg transition-all duration-300 font-medium tracking-wide relative overflow-hidden group/btn js-exit-link">
                                    <span class="relative z-10 flex items-center justify-center gap-2">
                                        INVENTORIA <span class="opacity-0 group-hover/btn:opacity-100 transition-opacity">→</span>
                                    </span>
                                </a>
                                <a href="https://bilanmpi.on-forge.com" class="magnetic-btn block w-full py-4 bg-transparent hover:bg-white/10 border border-white/10 text-center rounded-lg transition-all duration-300 text-sm font-medium js-exit-link">
                                    BILAN MPI
                                </a>
                                <a href="https://australeformation-planner.on-forge.com" class="magnetic-btn block w-full py-4 bg-transparent hover:bg-white/10 border border-white/10 text-center rounded-lg transition-all duration-300 text-sm font-medium js-exit-link">
                                    GENERATEUR DE PLANNING
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="magnetic-wrap group">
                    <div class="glass-panel rounded-2xl p-8 h-full relative overflow-hidden transition-all duration-500 hover:border-emerald-500/50">
                        <div class="absolute -inset-1 bg-gradient-to-r from-emerald-600 to-green-600 opacity-0 group-hover:opacity-20 blur-xl transition-opacity duration-500"></div>
                        
                        <div class="relative z-10 flex flex-col h-full">
                            <div class="flex items-center justify-between mb-8">
                                <div class="icon-box glow-emerald">
                                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                </div>
                                <div class="h-px flex-grow mx-4 bg-white/10 group-hover:bg-emerald-500/50 transition-colors"></div>
                                <span class="text-xs font-mono text-emerald-400">ID: AMC-02</span>
                            </div>
                            <img src="{{asset('img/2.png')}}" alt="" width="100"/>
                            <h2 class="text-3xl font-bold mb-2 group-hover:translate-x-2 transition-transform duration-300">AMC Solution</h2>
                            <p class="text-gray-400 text-sm mb-8 leading-relaxed">Plateforme centrale de management et supervision.</p>
                            
                            <div class="mt-auto">
                                <a href="https://amc-manager.on-forge.com" class="magnetic-btn block w-full py-4 bg-emerald-500/10 hover:bg-emerald-500 hover:text-white border border-emerald-500/30 text-emerald-400 text-center rounded-lg transition-all duration-300 font-bold tracking-wide js-exit-link">
                                    CONNEXION
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="magnetic-wrap group">
                    <div class="glass-panel rounded-2xl p-8 h-full relative overflow-hidden transition-all duration-500 hover:border-orange-500/50">
                        <div class="absolute -inset-1 bg-gradient-to-r from-orange-600 to-red-600 opacity-0 group-hover:opacity-20 blur-xl transition-opacity duration-500"></div>
                        
                        <div class="relative z-10 flex flex-col h-full">
                            <div class="flex items-center justify-between mb-8">
                                <div class="icon-box glow-orange">
                                    <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                </div>
                                <div class="h-px flex-grow mx-4 bg-white/10 group-hover:bg-orange-500/50 transition-colors"></div>
                                <span class="text-xs font-mono text-orange-400">ID: CAP-03</span>
                            </div>
                            <img src="{{asset('img/4.png')}}" width="100" alt="" />
                            <h2 class="text-3xl font-bold mb-2 group-hover:translate-x-2 transition-transform duration-300">Cap Avenir</h2>
                            <p class="text-gray-400 text-sm mb-8 leading-relaxed">Interface d'administration pour l'association.</p>
                            
                            <div class="mt-auto">
                                <a href="https://capavenir-manager.on-forge.com" class="magnetic-btn block w-full py-4 bg-orange-500/10 hover:bg-orange-500 hover:text-white border border-orange-500/30 text-orange-400 text-center rounded-lg transition-all duration-300 font-bold tracking-wide js-exit-link">
                                    ACCÉDER
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-700">
                    <div class="glass-panel rounded-2xl p-8 h-full border-dashed border-white/20 relative">
                        <div class="absolute inset-0 bg-black/50 z-20 flex items-center justify-center backdrop-blur-[2px]">
                            <div class="bg-black/80 border border-white/10 px-4 py-2 rounded text-xs font-mono text-gray-400 flex items-center gap-2">
                                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                ACCESS DENIED // DEV
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-col h-full opacity-40">
                             <div class="flex items-center justify-between mb-8">
                                <div class="icon-box glow-gray">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <div class="h-px flex-grow mx-4 bg-white/10"></div>
                                <span class="text-xs font-mono">ID: CAB-XX</span>
                            </div>
                            <img src="{{asset('img/3.png')}}" alt="" width="100"/>
                            <h2 class="text-3xl font-bold mb-2">CAB Gestion</h2>
                            <p class="text-gray-400 text-sm">Module en cours de construction.</p>
                        </div>
                    </div>
                </div>

            </div>
        </main>

        <footer class="mt-12 text-center">
            <p class="text-[10px] text-gray-600 font-mono tracking-[0.2em] uppercase">Secure Connection • WebGL Enabled • v4.0.1</p>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    <script src="https://twgljs.org/dist/4.x/twgl-full.min.js"></script>

    <script id="vs" type="x-shader/x-vertex">
        attribute vec4 position;
        void main() { gl_Position = position; }
    </script>
    <script id="fs" type="x-shader/x-fragment">
        precision mediump float;
        uniform vec2 u_resolution;
        uniform float u_time;
        uniform vec2 u_mouse;

        // Fonction de bruit pseudo-aléatoire
        float random (in vec2 _st) {
            return fract(sin(dot(_st.xy, vec2(12.9898,78.233))) * 43758.5453123);
        }

        // Bruit de Perlin simplifié
        float noise (in vec2 _st) {
            vec2 i = floor(_st);
            vec2 f = fract(_st);
            float a = random(i);
            float b = random(i + vec2(1.0, 0.0));
            float c = random(i + vec2(0.0, 1.0));
            float d = random(i + vec2(1.0, 1.0));
            vec2 u = f * f * (3.0 - 2.0 * f);
            return mix(a, b, u.x) + (c - a)* u.y * (1.0 - u.x) + (d - b) * u.x * u.y;
        }

        #define NUM_OCTAVES 5
        float fbm ( in vec2 _st) {
            float v = 0.0;
            float a = 0.5;
            vec2 shift = vec2(100.0);
            mat2 rot = mat2(cos(0.5), sin(0.5), -sin(0.5), cos(0.50));
            for (int i = 0; i < NUM_OCTAVES; ++i) {
                v += a * noise(_st);
                _st = rot * _st * 2.0 + shift;
                a *= 0.5;
            }
            return v;
        }

        void main() {
            vec2 st = gl_FragCoord.xy/u_resolution.xy;
            st.x *= u_resolution.x/u_resolution.y;

            vec2 mouse = u_mouse/u_resolution;
            
            vec3 color = vec3(0.0);
            
            // Mouvement du "liquide"
            vec2 q = vec2(0.);
            q.x = fbm( st + 0.00 * u_time);
            q.y = fbm( st + vec2(1.0));

            vec2 r = vec2(0.);
            r.x = fbm( st + 1.0*q + vec2(1.7,9.2)+ 0.15*u_time );
            r.y = fbm( st + 1.0*q + vec2(8.3,2.8)+ 0.126*u_time);

            float f = fbm(st+r);

            // Palette de couleurs "Cyberpunk Corporate" (Bleu profond, Cyan, Violet subtil)
            // Modification dynamique selon la souris
            float mouseDist = distance(st, mouse * vec2(u_resolution.x/u_resolution.y, 1.0));
            float interact = smoothstep(0.5, 0.0, mouseDist);

            color = mix(vec3(0.05, 0.05, 0.15), vec3(0.1, 0.4, 0.6), clamp((f*f)*4.0,0.0,1.0));
            color = mix(color, vec3(0.1, 0.1, 0.3), clamp(length(q),0.0,1.0));
            color = mix(color, vec3(0.1, 0.8, 1.0), clamp(length(r.x),0.0,1.0));

            // Ajouter un éclat là où est la souris
            color += vec3(0.2, 0.5, 1.0) * interact * 0.3;

            gl_FragColor = vec4((f*f*f+.6*f*f+.5*f)*color,1.);
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            
            // --- 1. WEBGL BACKGROUND SETUP ---
            const canvas = document.getElementById("gl-canvas");
            const gl = canvas.getContext("webgl");
            const programInfo = twgl.createProgramInfo(gl, ["vs", "fs"]);
            const arrays = {
                position: [-1, -1, 0, 1, -1, 0, -1, 1, 0, -1, 1, 0, 1, -1, 0, 1, 1, 0],
            };
            const bufferInfo = twgl.createBufferInfoFromArrays(gl, arrays);
            let mouseX = 0, mouseY = 0;

            function render(time) {
                twgl.resizeCanvasToDisplaySize(gl.canvas);
                gl.viewport(0, 0, gl.canvas.width, gl.canvas.height);
                const uniforms = {
                    u_time: time * 0.0005,
                    u_resolution: [gl.canvas.width, gl.canvas.height],
                    u_mouse: [mouseX, gl.canvas.height - mouseY], // Invert Y for GL
                };
                gl.useProgram(programInfo.program);
                twgl.setBuffersAndAttributes(gl, programInfo, bufferInfo);
                twgl.setUniforms(programInfo, uniforms);
                twgl.drawBufferInfo(gl, bufferInfo);
                requestAnimationFrame(render);
            }
            requestAnimationFrame(render);

            // --- 2. LOGIQUE D'INTERACTION ---
            
            // Mise à jour de l'horloge
            setInterval(() => {
                const now = new Date();
                document.getElementById('clock').innerText = now.toLocaleTimeString();
            }, 1000);

            // Gestionnaire de la souris globale
            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                
                // Custom Cursor Follow
                const cursor = document.getElementById('cursor');
                gsap.to(cursor, {
                    x: e.clientX,
                    y: e.clientY,
                    duration: 0.1,
                    ease: "power2.out"
                });
            });

            // --- 3. EFFET "DECRYPTION" DU TEXTE ---
            const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            document.querySelectorAll(".scramble-text, h1, h2").forEach(element => {
                element.dataset.value = element.innerText; // Store original text
                
                element.addEventListener('mouseenter', event => {
                    let iteration = 0;
                    clearInterval(event.target.interval);
                    
                    event.target.interval = setInterval(() => {
                        event.target.innerText = event.target.innerText
                            .split("")
                            .map((letter, index) => {
                                if(index < iteration) {
                                    return event.target.dataset.value[index];
                                }
                                return letters[Math.floor(Math.random() * 26)];
                            })
                            .join("");
                        
                        if(iteration >= event.target.dataset.value.length){ 
                            clearInterval(event.target.interval);
                        }
                        
                        iteration += 1 / 3;
                    }, 30);
                });
                // Trigger once on load
                const e = new Event('mouseenter');
                element.dispatchEvent(e);
            });

            // --- 4. BOUTONS MAGNÉTIQUES ---
            // Le bouton suit la souris quand on est proche, puis "snap" en place
            const magnets = document.querySelectorAll('.magnetic-wrap');
            const cursor = document.getElementById('cursor');

            magnets.forEach((magnet) => {
                magnet.addEventListener('mousemove', (e) => {
                    const rect = magnet.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;
                    
                    // Bouger le conteneur légèrement
                    gsap.to(magnet, {
                        x: x * 0.1,
                        y: y * 0.1,
                        duration: 1,
                        ease: "elastic.out(1, 0.3)"
                    });

                    // Cursor grow
                    cursor.classList.add('hovered');
                });

                magnet.addEventListener('mouseleave', () => {
                    gsap.to(magnet, { x: 0, y: 0, duration: 1, ease: "elastic.out(1, 0.3)" });
                    cursor.classList.remove('hovered');
                });
            });


            // --- 5. ANIMATION D'ENTRÉE ET SORTIE ---
            const app = document.getElementById('app');
            
            // Intro
            gsap.set(app, { opacity: 1 }); // Reveal container
            gsap.from("header", { duration: 1.5, y: -50, opacity: 0, ease: "power3.out", delay: 0.2 });
            gsap.from(".glass-panel", { 
                duration: 1.5, 
                y: 0, 
                opacity: 1, 
                stagger: 0.2, 
                ease: "power4.out",
                delay: 0.5 
            });

            // Sortie (Hyper-Jump)
            document.querySelectorAll('.js-exit-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = this.href;
                    
                    // Timeline de sortie spectaculaire
                    const tl = gsap.timeline({
                        onComplete: () => window.location.href = target
                    });

                    // 1. Tout le texte se transforme en code binaire aléatoire puis disparaît
                    tl.to("h1, h2, p, a", { duration: 0.2, opacity: 0, scale: 1.1 })
                    
                    // 2. Les cartes se font aspirer vers le centre
                      .to(".glass-panel", { duration: 0.5, scale: 0, rotation: 10, opacity: 0, stagger: 0.1, ease: "back.in(2)" })
                      
                    // 3. Le shader background accélère (simulation de vitesse lumière)
                      .to(canvas, { duration: 0.5, scale: 1.5, filter: "brightness(200%) blur(10px)", ease: "expo.in" });
                });
            });

        });
    </script>
</body>
</html>