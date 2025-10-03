ALTER TABLE `users`
ADD COLUMN `role` varchar(16) NOT NULL
AFTER `remember_token`;

INSERT INTO `users` (
  `name`, `email`, `email_verified_at`,
  `password`,
  `role`, `created_at`, `updated_at`
)
VALUES 
(
  'Administrator', 'admin@my-db.com', CURRENT_TIMESTAMP(),
  '$2y$10$RGuRSLsfJUdaELnY9ZUER.w4pqSyoHeiluriZRahtJB1nWBNYUuwW',
  'ADMIN', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP() 
),
(
  'User', 'user@my-db.com', CURRENT_TIMESTAMP(),
  '$2y$10$ggNGqa1vmznIwhTFcSRkoeBEI5ZN/EDYTwQAfBeIHYogpxGCNzfJG',
  'USER', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP() 
);

-- both password = 1234
