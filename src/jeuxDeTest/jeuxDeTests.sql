INSERT INTO `joueur` (`id`, `nom`, `mail`, `ddn`, `roles`, `password`)
VALUES
    (1, 'Lucas Legendre', 'lucas.legendre@example.com', '1990-05-15 00:00:00', '["ROLE_PLAYER"]', '$2y$13$fakeHashForLucas'),
    (2, 'Marie Dupont', 'marie.dupont@example.com', '1985-08-22 00:00:00', '["ROLE_PLAYER"]', '$2y$13$fakeHashForMarie'),
    (3, 'Pierre Martin', 'pierre.martin@example.com', '1992-11-10 00:00:00', '["ROLE_PLAYER"]', '$2y$13$fakeHashForPierre'),
    (4, 'Sophie Bernard', 'sophie.bernard@example.com', '1988-03-18 00:00:00', '["ROLE_PLAYER"]', '$2y$13$fakeHashForSophie'),
    (5, 'Thomas Leroy', 'thomas.leroy@example.com', '1995-07-30 00:00:00', '["ROLE_PLAYER"]', '$2y$13$fakeHashForThomas'),
    (6, 'Camille Petit', 'camille.petit@example.com', '1993-09-05 00:00:00', '["ROLE_PLAYER"]', '$2y$13$fakeHashForCamille'),
    (7, 'Julien Moreau', 'julien.moreau@example.com', '1987-12-12 00:00:00', '["ROLE_PLAYER"]', '$2y$13$fakeHashForJulien'),

INSERT INTO `rencontre` (`id`, `joueur1_id`, `joueur2_id`, `gagnant_id`)
VALUES
    (1, 1, 2, 1),
    (2, 3, 4, 3),
    (3, 5, 6, 6),

INSERT INTO `resultat` (`id`, `score_joueur1`, `score_joueur2`, `Rencontre_id`)
VALUES
    (1, 3, 1, 1), 
    (2, 3, 0, 2),
    (3, 1, 3, 3),
