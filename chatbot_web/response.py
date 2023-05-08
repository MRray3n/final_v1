# things we need for NLP
from time import gmtime, strftime
from datetime import datetime
from flask_cors import CORS
from urllib.parse import *
from nltk.stem.snowball import SnowballStemmer
import json
import pickle
import random
import tensorflow as tf
import tflearn
import numpy as np
from flask_mysqldb import MySQL
from flask import Flask, render_template, request, jsonify, make_response
from nltk.corpus import stopwords
from nltk import word_tokenize
from nltk.tokenize import *
import re

import nltk
from nltk.stem.lancaster import *
stemmer = nltk.stem.SnowballStemmer('french')
# things we need for Tensorflow

# restore all of our data structures
data = pickle.load(open("training_data", "rb"))
words = data['words']
classes = data['classes']
train_x = data['train_x']
train_y = data['train_y']

# import our chat-bot intents file
with open('intents.json', encoding='utf-8') as json_data:
    intents = json.load(json_data)
# %%
# Build neural network
net = tflearn.input_data(shape=[None, len(train_x[0])])
net = tflearn.fully_connected(net, 16)
net = tflearn.fully_connected(net, 16)
net = tflearn.fully_connected(net, len(train_y[0]), activation='softmax')
net = tflearn.regression(net)

# Define model and setup tensorboard
model = tflearn.DNN(net, tensorboard_dir='tflearn_logs')


stemmer = SnowballStemmer(language='french')
# %%

global results


def clean_up_sentence(sentence):
    # Tokeniser la phrase
    doc = word_tokenize(sentence, language="french")
    print(doc)
    # Retourner le texte de chaque phrase
    stopWords = list(stopwords.words('french'))
    print(type(stopWords))
    t = ['?', '.', '!', '"', ',', "'", ']', '[', "''", '``']
    stopWords.extend(t)
    clean_words = []
    for token in doc:
        if token not in stopWords:
            clean_words.append(token)

    print(clean_words)
    print("doc=")
    print(doc)
    sentence_words = [stemmer.stem(X.lower()) for X in doc]
    print(sentence_words, type(sentence_words))
    return sentence_words

# return bag of words array: 0 or 1 for each word in the bag that exists in the sentence


def bow(sentence, words, show_details=False):
    # tokenize the pattern
    sentence_words = clean_up_sentence(sentence)
    # bag of words
    bag = [0]*len(words)
    for s in sentence_words:
        for i, w in enumerate(words):
            if w == s:
                bag[i] = 1
                if show_details:
                    print("found in bag: %s" % w)
    print(np.array(bag))
    return (np.array(bag))


# %%
# load our saved model
model.load('./model.tflearn')

# create a data structure to hold user context
context = {}

ERROR_THRESHOLD = 0.50


def classify(sentence):
    # generate probabilities from the model
    results = model.predict([bow(sentence, words)])[0]
    # filter out predictions below a threshold
    results = [[i, r] for i, r in enumerate(results) if r > ERROR_THRESHOLD]
    # sort by strength of probability
    results.sort(key=lambda x: x[1], reverse=True)
    return_list = []
    for r in results:
        return_list.append((classes[r[0]], r[1]))
    # return tuple of intent and probability
    return return_list


def response(sentence, userID, show_details=False):
    results = classify(sentence)
    print(results)
    # if we have a classification then find the matching intent tag
    if results:
        # loop as long as there are matches to process
        while results:
            for i in intents['intents']:
                # find a tag matching the first result
                if i['tag'] == results[0][0]:
                    # set context for this intent if necessary
                    if 'context_set' in i:
                        if show_details:
                            print('context:', i['context_set'])
                        context[userID] = i['context_set']
                        
                    # check if this intent is contextual and applies to this user's conversation
                    if not 'context_filter' in i or \
                            (userID in context and 'context_filter' in i and i['context_filter'] == context[userID]):
                        if show_details:
                            print('tag:', i['tag'])
                        # a random response from the intent
                        encoded_text = random.choice(
                            i['responses']).encode('utf-8')
                        decoded_text_utf = encoded_text.decode('utf-8')
                        print("i=",i)
                        print("context",context)
                        return quote(decoded_text_utf),results[0][0]
                    else:
                        return "je ne comprends pas ce que tu dis"

            results.pop(0)
    else:
        return "je ne comprends pas ce que tu dis"


# import unicodedata
app = Flask(__name__)
CORS(app)
app.static_folder = 'static'
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'chatbot_db'
mysql = MySQL(app)
import re
@app.route("/")
def home():
    return render_template("base.html")
@app.route("/",methods=['POST'])
@app.route("/get", methods=['GET'])
def get_bot_response():
    
 
    userID = request.args.get('userid') or request.get_json().get("UserID")
    userText = request.args.get('msg') or request.get_json().get("message")
        
    decoded_text = unquote(userText)    
#userID
    cur = mysql.connection.cursor()
    cur.execute("SELECT userID FROM users WHERE userID = %s", (userID,))
    results = cur.fetchone()
    print(results)
    if results is None:
        cur.execute("INSERT INTO users (userID) VALUES (%s)", (userID,))
#disponibilite  
    try:  
        if "disponibilite" or "disponibilité" in decoded_text:
            # Extract the name using regular expression
            name_match = re.search(r'\b(\w+)\b\s+(\w+)', decoded_text.split('disponibilite')[1])
            # Get the name group from the regex match
            last_name = name_match.group(1)
            first_name = name_match.group(2)
            professor_name = last_name +" "+ first_name
            print("profname", professor_name )
            cur = mysql.connection.cursor()
            cur.execute('SELECT disponibilite FROM disponibilite_prof2 WHERE name = (%s)', (professor_name,))
            result = cur.fetchone()
            print("res",result)
            if result is None:
                res = "prof name ........."
                cur.connection.commit()
                cur.close()
                if request.path == "/get":
                    return sendresponse_android(res)
                elif request.path == "/" :
                    return sendresponse_web(res)
            else:
                cur.connection.commit()
                cur.close()
                if request.path == "/get":
                    return sendresponse_android(result)
                elif request.path == "/" :
                    return sendresponse_web(result)
    except:
        print("id=", userID)
        
        res = response(str(decoded_text), userID, show_details=False)
        tag=res[1]
        if res[0]== "j" :
            res ="je ne comprends pas ce que tu dis"
        else:
            
            res=res[0]
            
            
        print("0",res)
        print("1",res[1])
        print("tag",tag)
        if tag == "Aide financiere-1" :
            
            if request.path == "/get":
                res="https://bit.ly/tnbourse"
                return sendresponse_android(res)
            elif request.path == "/" :
                res = str(res) + "<a href=\"https://bit.ly/tnbourse\" target=\"_blank\">https://bit.ly/tnbourse</a>"
                return sendresponse_web(res)
#vacance
        if res=="vacance"  :
            cur = mysql.connection.cursor()
            cur.execute(
                "SELECT * FROM vacation ORDER BY id")
            result = cur.fetchall()
            print("result", result)
            if result is None:
                res="Il n'y a pas de vacance"
            else :
                # execute a PRAGMA statement to retrieve the column names from the news table
                cur.execute("DESCRIBE vacation")
                columns = [column[1] for column in cur.fetchall()]
                # determine the maximum width of each column
                max_widths = [len(column) for column in columns]
                for row in result:
                    for i, value in enumerate(row):
                        max_widths[i] = max(max_widths[i], len(str(value)))

                # initialize the output variable with the column headers
            
                output = ''
                # add the data rows
                for row in result:
                    output += "__________" + '<br>'
                    output += "nom de vacance  :"+ str(row[1]).ljust(max_widths[1]) + '<tr>' + '<br>'
                    output += "date:"+ str(row[2]).ljust(max_widths[2]) + '<br>'
                    output += "__________" + '<br>'
                # print the output variable
                print(output)
                res = output
# actualites
        if res == "news" :
            cur = mysql.connection.cursor()
            cur.execute(
                "SELECT * FROM news ORDER BY id")
            result = cur.fetchall()
            print("result", result)
            if result is None:
                res="Il n'y a pas de nouvelles actualites"
            else :
                # execute a PRAGMA statement to retrieve the column names from the news table
                cur.execute("DESCRIBE news")
                columns = [column[1] for column in cur.fetchall()]
                # determine the maximum width of each column
                max_widths = [len(column) for column in columns]
                for row in result:
                    for i, value in enumerate(row):
                        max_widths[i] = max(max_widths[i], len(str(value)))

                # initialize the output variable with the column headers
            
                output = ''
                # add the data rows
                for row in result:
                    output += "__________" + '<br>'
                    output += "Event Name :"+ str(row[1]).ljust(max_widths[1]) + '<tr>' + '<br>'
                    output += "Description:"+ str(row[2]).ljust(max_widths[2]) + '<br>'
                    output += "__________" + '<br>'
                # print the output variable
                print(output)
                res = output
##je ne comprends pas                        
        if res == "je ne comprends pas ce que tu dis":
            cur = mysql.connection.cursor()
            # Check if the question already exists in the unanswered_questions table
            cur.execute(
                "SELECT no_asks FROM unanswered WHERE question = %s", (decoded_text,))
            result = cur.fetchone()
            if result is None:
                # If the question doesn't exist in the table, insert it with no_asks 1
                cur.execute(
                    "INSERT INTO unanswered (question, no_asks) VALUES (%s, 1)", (decoded_text,))
            else:
                # If the question already exists, increment the number_questions value
                no_asks = result[0] + 1
                cur.execute(
                    "UPDATE unanswered SET no_asks = %s WHERE question = %s", (no_asks, decoded_text))
        # Commit changes to the database
        cur.connection.commit()
        cur.close()
        encoded_text = res.encode('utf-8')
        decoded_text_utf = encoded_text.decode('utf-8')
        if request.path == "/get":
            return sendresponse_android(decoded_text_utf)
        elif request.path == "/" :
            return sendresponse_web(decoded_text_utf)

                
def sendresponse_android(decoded_text_utf):
    print(jsonify(cnt=decoded_text_utf))
    return jsonify(cnt=decoded_text_utf)

def sendresponse_web(decoded_text_utf):
    mes = {"answer": decoded_text_utf}
    print(mes)
    return jsonify(mes)

if __name__ == "__main__":
    app.run(debug=True, port=7777, host="0.0.0.0")
    

# %%
