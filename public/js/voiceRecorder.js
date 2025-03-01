class VoiceRecorder {
    constructor(targetTextArea, recordButton, statusElement) {
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.targetTextArea = targetTextArea;
        this.recordButton = recordButton;
        this.statusElement = statusElement;
        this.isRecording = false;

        this.recordButton.addEventListener('click', () => this.toggleRecording());
    }

    async toggleRecording() {
        if (!this.isRecording) {
            await this.startRecording();
        } else {
            await this.stopRecording();
        }
    }

    async startRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            this.mediaRecorder = new MediaRecorder(stream, {
                mimeType: 'audio/webm'
            });
            this.audioChunks = [];

            this.mediaRecorder.addEventListener('dataavailable', event => {
                this.audioChunks.push(event.data);
            });

            this.mediaRecorder.addEventListener('stop', () => this.handleRecordingComplete());

            this.mediaRecorder.start();
            this.isRecording = true;
            this.recordButton.classList.add('recording');
            this.recordButton.innerHTML = '<i class="fas fa-stop"></i> Stop Recording';
            this.updateStatus('Recording...');
        } catch (error) {
            console.error('Error accessing microphone:', error);
            this.updateStatus('Error: Could not access microphone');
        }
    }

    async stopRecording() {
        if (this.mediaRecorder && this.isRecording) {
            this.mediaRecorder.stop();
            this.isRecording = false;
            this.recordButton.classList.remove('recording');
            this.recordButton.innerHTML = '<i class="fas fa-microphone"></i> Start Recording';
            this.updateStatus('Processing audio...');
        }
    }

    async handleRecordingComplete() {
        const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
        
        // Convert to WAV format
        const wavBlob = await this.convertToWav(audioBlob);
        
        const formData = new FormData();
        formData.append('audio', wavBlob, 'recording.wav');

        try {
            const response = await fetch('/api/transcribe', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.error || 'Transcription failed');
            }

            if (data.text) {
                const currentText = this.targetTextArea.value;
                this.targetTextArea.value = currentText + (currentText ? ' ' : '') + data.text;
                this.updateStatus('Transcription complete!');
            } else {
                throw new Error('No transcription received');
            }
        } catch (error) {
            console.error('Error during transcription:', error);
            this.updateStatus('Error: ' + error.message);
        }
    }

    async convertToWav(blob) {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const arrayBuffer = await blob.arrayBuffer();
        const audioBuffer = await audioContext.decodeAudioData(arrayBuffer);
        
        const numberOfChannels = audioBuffer.numberOfChannels;
        const length = audioBuffer.length;
        const sampleRate = audioBuffer.sampleRate;
        const wavBuffer = audioContext.createBuffer(numberOfChannels, length, sampleRate);
        
        for (let channel = 0; channel < numberOfChannels; channel++) {
            const channelData = audioBuffer.getChannelData(channel);
            wavBuffer.getChannelData(channel).set(channelData);
        }
        
        const wavBlob = await this.bufferToWav(wavBuffer);
        return wavBlob;
    }

    bufferToWav(buffer) {
        const numberOfChannels = buffer.numberOfChannels;
        const sampleRate = buffer.sampleRate;
        const length = buffer.length;
        const wavDataView = this.createWavDataView(numberOfChannels, sampleRate, length);
        
        let offset = 44;
        for (let i = 0; i < buffer.length; i++) {
            for (let channel = 0; channel < numberOfChannels; channel++) {
                const sample = buffer.getChannelData(channel)[i];
                wavDataView.setInt16(offset, sample * 0x7FFF, true);
                offset += 2;
            }
        }
        
        return new Blob([wavDataView], { type: 'audio/wav' });
    }

    createWavDataView(numberOfChannels, sampleRate, length) {
        const buffer = new ArrayBuffer(44 + length * numberOfChannels * 2);
        const view = new DataView(buffer);
        
        // Write WAV header
        this.writeString(view, 0, 'RIFF');
        view.setUint32(4, 36 + length * numberOfChannels * 2, true);
        this.writeString(view, 8, 'WAVE');
        this.writeString(view, 12, 'fmt ');
        view.setUint32(16, 16, true);
        view.setUint16(20, 1, true);
        view.setUint16(22, numberOfChannels, true);
        view.setUint32(24, sampleRate, true);
        view.setUint32(28, sampleRate * numberOfChannels * 2, true);
        view.setUint16(32, numberOfChannels * 2, true);
        view.setUint16(34, 16, true);
        this.writeString(view, 36, 'data');
        view.setUint32(40, length * numberOfChannels * 2, true);
        
        return view;
    }

    writeString(view, offset, string) {
        for (let i = 0; i < string.length; i++) {
            view.setUint8(offset + i, string.charCodeAt(i));
        }
    }

    updateStatus(message) {
        if (this.statusElement) {
            this.statusElement.textContent = message;
        }
    }
}

// Add some CSS styles
const style = document.createElement('style');
style.textContent = `
    .voice-record-btn {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 10px 0;
    }

    .voice-record-btn:hover {
        background-color: #0056b3;
    }

    .voice-record-btn.recording {
        background-color: #dc3545;
        animation: pulse 1.5s infinite;
    }

    .voice-record-status {
        margin-top: 5px;
        font-size: 0.9em;
        color: #666;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
`;
document.head.appendChild(style);
