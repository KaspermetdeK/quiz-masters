// State
let totalScore = 0;
let answered = false;
let correctAnswerId = null;

// DOM elementen
const questionText = document.getElementById('question_text');
const optionsContainer = document.getElementById('question_options');
const submitBtn = document.getElementById('submitBtn');
const feedback = document.getElementById('feedback');
const scoreValue = document.getElementById('totalScore');

// Event listener
submitBtn.addEventListener('click', handleSubmit);

// Controleer antwoord
function handleSubmit() {
    if (answered) return;
    
    const selectedAnswer = document.querySelector('input[name="answer"]:checked');
    
    if (!selectedAnswer) {
        showFeedback('Selecteer alstublieft een antwoord', false);
        return;
    }
    
    answered = true;
    
    const isCorrect = selectedAnswer.id === correctAnswerId;
    
    if (isCorrect) {
        totalScore++;
        scoreValue.textContent = totalScore;
        showFeedback('Correct!', true);
    } else {
        showFeedback('Onjuist!', false);
    }
    
    disableAllOptions();
}

// Toon feedback
function showFeedback(message, isCorrect) {
    feedback.textContent = message;
    feedback.className = 'feedback ' + (isCorrect ? 'correct' : 'incorrect');
}

// Disable opties
function disableAllOptions() {
    document.querySelectorAll('input[name="answer"]').forEach(radio => {
        radio.disabled = true;
    });
}

// Initialiseer: stel hier de correcte antwoord ID in
// Bijvoorbeeld: correctAnswerId = 'option_2' voor Parijs
correctAnswerId = 'option_2';