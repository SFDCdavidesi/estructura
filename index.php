<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionarios de Autoevaluación UAX</title>
    <style>
        :root {
            --primary-color: #004a99;
            --success-color: #28a745;
            --error-color: #dc3545;
            --bg-color: #f4f7f6;
            --card-bg: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            max-width: 800px;
            width: 100%;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
        }

        .scoreboard {
            position: sticky;
            top: 10px;
            z-index: 100;
            background: var(--card-bg);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .score-item.correct { color: var(--success-color); }
        .score-item.wrong { color: var(--error-color); }

        .question-card {
            background: var(--card-bg);
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .question-text {
            font-size: 1.1rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .options-list {
            list-style: none;
            padding: 0;
        }

        .option-item {
            padding: 12px;
            margin: 8px 0;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .option-item:hover {
            background-color: #f0f4f8;
            border-color: var(--primary-color);
        }

        .option-item.selected-correct {
            background-color: #d4edda;
            border-color: var(--success-color);
            color: #155724;
        }

        .option-item.selected-wrong {
            background-color: #f8d7da;
            border-color: var(--error-color);
            color: #721c24;
        }

        .disabled {
            pointer-events: none;
            opacity: 0.8;
        }

        .unit-tag {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }

        @media (max-width: 600px) {
            body { padding: 10px; }
            .scoreboard { font-size: 1rem; }
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Simulador de Examen UAX</h1>
        <p>Unidades Didácticas 1 - 6</p>
    </header>

    <div class="scoreboard">
        <div class="score-item correct">Aciertos: <span id="correct-count">0</span></div>
        <div class="score-item">Preguntas: <span id="total-answered">0</span> / <span id="total-questions">0</span></div>
        <div class="score-item wrong">Fallos: <span id="wrong-count">0</span></div>
    </div>

    <div id="quiz-container">
        </div>
</div>

<script>
    const questions = [
        // UD1
        { unit: "UD1", q: "¿A qué generación pertenecen los transistores?", options: ["Tercera", "Primera", "Cuarta", "Segunda"], correct: 3 },
        { unit: "UD1", q: "En una memoria:", options: ["Tenemos tantos bits como 2^líneas", "La cantidad total de datos es potencia de 2", "En cada posición hay un dato útil", "El número de posiciones es 2^bits de entrada"], correct: 1 },
        { unit: "UD1", q: "¿Qué arquitectura recomendarías para un sistema empotrado?", options: ["Macintosh", "Harvard", "Von Neumann", "Windows"], correct: 1 },
        { unit: "UD1", q: "Es arquitectura Von Neumann si...", options: ["Bus de datos e instrucciones es compartido", "Datos y control comparten bus", "Instrucciones y datos mismo tamaño y bus", "Ancho de palabra != tamaño instrucción"], correct: 0 },
        { unit: "UD1", q: "Una memoria volátil se caracteriza por:", options: ["Almacenar datos", "Perder información sin flujo eléctrico", "No necesitar energía", "Ninguna"], correct: 1 },
        { unit: "UD1", q: "El CP (Contador de Programa) apunta a:", options: ["Siguiente instrucción", "Instrucción en curso", "Instrucción previa", "Ninguna"], correct: 0 },
        { unit: "UD1", q: "El lenguaje máquina es:", options: ["Muy alto nivel", "Bajo nivel", "Alto nivel", "Ninguna"], correct: 1 },
        
        // UD2
        { unit: "UD2", q: "Respecto al reloj de un procesador:", options: ["Difícil comparar arquitecturas distintas", "Más frecuencia siempre es más rápido", "Señal sinusoidal 0-5V", "Más MIPS siempre es más rápido"], correct: 0 },
        { unit: "UD2", q: "Respecto al CPI:", options: ["A mayor CPI menos MIPS", "Más ciclos es siempre más lento", "Depende de frecuencia", "Todas duran lo mismo"], correct: 0 },
        { unit: "UD2", q: "Valor MIPS con 2 MHz y 3 instrucciones/ciclo:", options: ["6", "1.5", "No se sabe", "666.6"], correct: 0 },
        
        // UD3
        { unit: "UD3", q: "El lenguaje ensamblador:", options: ["Poco parecido entre CPUs", "Igual que código máquina", "Bajo nivel por cercanía al hardware", "Uso corriente"], correct: 2 },
        { unit: "UD3", q: "Característica principal de MIPS32:", options: ["Sin aritmética", "Instrucciones complejas", "Conjunto reducido de instrucciones", "CISC"], correct: 2 },
        { unit: "UD3", q: "Instrucción MIPS32 para sumar registros:", options: ["SUB", "ADD", "DIV", "MUL"], correct: 1 },
        { unit: "UD3", q: "¿Qué es Little Endian?", options: ["Sin orden", "Aleatorio", "Byte menos significativo primero", "Byte más significativo primero"], correct: 2 },
        
        // UD4
        { unit: "UD4", q: "Procesos con memoria compartida:", options: ["Mismo sistema de ficheros", "Hijos mismo padre", "Padre-hijo", "Mismo usuario"], correct: 0 },
        { unit: "UD4", q: "En un pipe:", options: ["Solo unidireccional", "No bidireccional", "Sin bloqueos", "No entre máquinas distintas"], correct: 3 },
        { unit: "UD4", q: "Semaforo general con valor 2:", options: ["Sin procesos en R.C.", "Wait bloquea", "Puede haber procesos en R.C.", "Signal no afecta"], correct: 2 },
        
        // UD5
        { unit: "UD5", q: "Funciones de bibliotecas estáticas:", options: ["Integradas en ejecutables", "Llamada al sistema", "Sin reenlace tras update", "Sin recompilar"], correct: 0 },
        { unit: "UD5", q: "Las direcciones lógicas son:", options: ["Entrada a la MMU", "Referencias físicas", "Empiezan en 1", "Distintas para cada proceso"], correct: 0 },
        { unit: "UD5", q: "Particiones dinámicas tienen:", options: ["Fragm. Interna", "Fragm. Externa", "Zonas separadas", "Eficiencia fija"], correct: 1 },
        
        // UD6
        { unit: "UD6", q: "Tercer parámetro de 'open':", options: ["Solo si el archivo no existe", "Privilegios dueño", "Privilegios proceso", "Modo R/W"], correct: 0 },
        { unit: "UD6", q: "Controlador de dispositivo:", options: ["Hardware nuevo", "Software envía órdenes", "Escribe en registros", "Escribe en memoria principal"], correct: 3 },
        { unit: "UD6", q: "E/S por interrupciones:", options: ["Solo proceso usuario", "No interrumpible", "Siempre modo núcleo", "Proceso usuario atiende"], correct: 2 }
    ];

    let correctScore = 0;
    let wrongScore = 0;
    let answeredCount = 0;

    const quizContainer = document.getElementById('quiz-container');
    const correctDisplay = document.getElementById('correct-count');
    const wrongDisplay = document.getElementById('wrong-count');
    const totalAnsweredDisplay = document.getElementById('total-answered');
    const totalQuestionsDisplay = document.getElementById('total-questions');

    function initQuiz() {
        totalQuestionsDisplay.innerText = questions.length;
        questions.forEach((data, index) => {
            const card = document.createElement('div');
            card.className = 'question-card';
            card.innerHTML = `
                <span class="unit-tag">${data.unit}</span>
                <div class="question-text">${index + 1}. ${data.q}</div>
                <ul class="options-list" id="q${index}-options">
                    ${data.options.map((opt, i) => `
                        <li class="option-item" onclick="checkAnswer(${index}, ${i})">${opt}</li>
                    `).join('')}
                </ul>
            `;
            quizContainer.appendChild(card);
        });
    }

    window.checkAnswer = function(qIndex, selectedIndex) {
        const optionsList = document.getElementById(`q${qIndex}-options`);
        if (optionsList.classList.contains('disabled')) return;

        const items = optionsList.getElementsByTagName('li');
        const isCorrect = selectedIndex === questions[qIndex].correct;

        if (isCorrect) {
            items[selectedIndex].classList.add('selected-correct');
            correctScore++;
        } else {
            items[selectedIndex].classList.add('selected-wrong');
            items[questions[qIndex].correct].classList.add('selected-correct');
            wrongScore++;
        }

        answeredCount++;
        optionsList.classList.add('disabled');
        updateScore();
    }

    function updateScore() {
        correctDisplay.innerText = correctScore;
        wrongDisplay.innerText = wrongScore;
        totalAnsweredDisplay.innerText = answeredCount;
    }

    initQuiz();
</script>

</body>
</html>