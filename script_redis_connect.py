import redis 
from datetime import datetime,timedelta
import json
import sys 

r_cli = redis.StrictRedis(host='localhost', port=6379, db=0)


def check_connection(user_mail) :
    """Vérifie le nombre de connexion de l'utilisateur dont le mail est en entrée, 
    print 1 si l'utilisateur peut se connecter et 0 sinon"""
    user = r_cli.get(user_mail)
    print(user)
    user_data = json.loads(user)
    last_login = datetime.fromisoformat(user_data["last_login"])
    d = datetime.now() - last_login

    # La dernière connexion était il y a plus de 10 min : aucun problème
    if d > timedelta(minutes=10):
        user_data['nb_connection'] = 1
        user_data['last_login'] = datetime.now().isoformat()
        user = json.dumps(user_data)
        r_cli.set(user_mail,user)
        
        print("1")

    # Intervalle de temps inférieure à 10 min
    else:
        # On vérifie si le client s'est connecté moins de 10 fois
        if user_data['nb_connection'] < 10:
            # On met à jour le nombre de connections
            user_data['nb_connection'] += 1
            print(f"Le client s'est connecté {user_data['nb_connection']} fois dans les 10 dernières minutes")
            user = json.dumps(user_data)
            r_cli.set(user_mail,user)
            print("1")
            
        else:
            print(f"Le client s'est connecté 10 fois dans les 10 dernières minutes")
            print("0")

if __name__ == '__main__' :
    check_connection(sys.argv[1])