from flask import Flask, request, jsonify
from cryptosteganography import CryptoSteganography
import os

app = Flask(__name__)
crypto_steganography = CryptoSteganography('NewCater')

@app.route('/encrypt', methods=['POST'])
def encrypt():
  # params: file_path, com_add, author_add, customer_add, price
  data = request.get_json()
  hidden_text = """{
    "com": {data.com},
    "author": {data.author},
    "customer": {data.customer},
    "price": {data.price},
  }
  """
  file_path = "../" + data['file_path']
  file_name = data['file_path'].split('/')[-1]
  new_file_name = "_encoded.".join(file_name.split('.'))
  new_path = "../" + "/".join(data['file_path'].split('/')[0:-1]) + "/" + new_file_name
  crypto_steganography.hide(file_path, new_path, hidden_text)
  cmd = "cp -R " + new_path + " /Users/buithanhtung/go/src/github.com/tungbt94/aes-image/image/" + new_file_name
  print('cmd: ', cmd)
  returned_value = os.system(cmd)  # returns the exit code in unix
  print('returned value:', returned_value)
  return jsonify(
    new_image=new_path
  )