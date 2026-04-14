import concurrent.futures
import requests
import json

# URL de l'API de Laravel
API_URL = "http://localhost:8000/api/movies/purchase"

# Seients que tothom intentarà comprar alhora ( IDs del 1 al 5 corresponents a la primera peli)
SEATS_TO_BUY = [1, 2, 3, 4, 5]

def attempt_purchase(user_id):
    payload = {"seat_ids": SEATS_TO_BUY}
    try:
        response = requests.post(API_URL, json=payload)
        return user_id, response.status_code, response.json()
    except Exception as e:
        return user_id, 0, {"message": str(e)}

def run_stress_test():
    print(f"--- INICIANT TEST D'ESTRÈS ---")
    print(f"Simulant 30 usuaris intentant comprar els seients {SEATS_TO_BUY} alhora...")
    
    with concurrent.futures.ThreadPoolExecutor(max_workers=30) as executor:
        # Llançem 30 peticions quasi simultànies
        futures = [executor.submit(attempt_purchase, i) for i in range(30)]
        results = [f.result() for f in concurrent.futures.as_completed(futures)]

    success_count = 0
    fail_count = 0
    
    for user_id, status, data in results:
        if status == 200 and data.get('success'):
            success_count += 1
            # print(f"Usuari {user_id:2}: ÈXIT")
        else:
            fail_count += 1
            # print(f"Usuari {user_id:2}: ERROR - {data.get('message')}")

    print("-" * 40)
    print(f"RESULTATS FINALS:")
    print(f" - Compres amb èxit: {success_count}")
    print(f" - Intents fallits:   {fail_count}")
    print("-" * 40)
    
    if success_count == 1:
        print("✅ TEST PASSAT: El sistema de bloqueig de BD ha evitat la doble venda.")
    elif success_count > 1:
        print("❌ TEST FALLIT: S'han venut els mateixos seients a més d'una persona!")
    else:
        print("❓ TEST INCONCLUSIU: Ningú ha pogut comprar (revisa la conexió o els IDs).")

if __name__ == "__main__":
    run_stress_test()
