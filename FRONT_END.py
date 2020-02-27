from flask import Flask
 
app = Flask(__name__)
 
@app.route('/hello')
def helloWorldHandler():
    return 'Hello World!'
 
app.run(host='0.0.0.0', port= 5000)



