import redis 
from datetime import datetime
import sys 
import json 

r_cli = redis.StrictRedis(host='localhost', port=6379, db=0)


def new_user(user) :
    """ Enregistre un nouveau client dans le serveur redis, à partir du fichier json en entrée, avec son mail, l'heure de sa connexion
    et initialise son nombre de connexion a 1"""
    with open(user, 'r') as f:
        data = json.load(f)
    data["last_login"] = datetime.now().isoformat()
    data["nb_connection"] = 1
    data_json = json.dumps(data)
    print(data_json)
    r_cli.set(data["email"],data_json)


if __name__ == '__main__' :
    new_user(sys.argv[1])