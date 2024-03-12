A remote végződésű scriptek nem a runneren, hanem egy távoli, production szerveren futnak.
Realease: kód release, git pull. Ez mindig automatikusan fut.
Deploy: docker-compose újraindítása, ez manuális. FONTOS: ez minden szerveren minden konténert újra indít!
A load balancer mögötti összes szerveren futnak ezek a jobok és scriptek. Ezekez intézik az 'all' végződésű shell scriptek.

Egy kivétel van, az adatbázis szerver. Neki saját jobja van, mivel nagyon ritkán változik, és sok minden függ tőle.

Ha új szerver kerül a clusterbe, 3 helyen kell hozzáadni az IP címét:
- Gitlab variables
- release-all-server
- deploy-all-server
- restart-service-on-all-server

Ha új Gitlab variable kerül be a rendszerbe, akkor ugyanezeket a scripteket kell frissíteni.