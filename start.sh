#!/bin/bash

echo "Choisissez l'option de lancement pour les services Docker:"
echo "1 - Base de données uniquement"
echo "2 - PhpMyAdmin uniquement"
echo "3 - Base de données et PhpMyAdmin"
echo "4 - Démarrer tous les services"
echo "5 - Arrêter tous les services"

read -p "Entrez votre choix (1-5): " choice

if [[ $choice -ne 5 ]]; then
  read -p "Souhaitez-vous lancer en mode détaché ? (y/n): " detached
  if [[ "$detached" == "y" ]]; then
    detached_flag="-d"
  else
    detached_flag=""
  fi
fi

case $choice in
  1)
    echo "Démarrage de la base de données seule..."
    docker compose up -d db $detached_flag
    ;;
  2)
    echo "Démarrage de PhpMyAdmin seule..."
    docker compose up -d phpmyadmin $detached_flag
    ;;
  3)
    echo "Démarrage de la base de données et de PhpMyAdmin..."
    docker compose up -d db phpmyadmin $detached_flag
    ;;
  4)
    echo "Démarrage de tous les services..."
    docker compose up -d $detached_flag
    ;;
  5)
    echo "Arrêt de tous les services..."
    docker compose down
    ;;
  *)
    echo "Option invalide. Veuillez choisir un nombre entre 1 et 5."
    ;;
esac
