-- Database: `import_csv`
-- Table structure for table `users`

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `clientName` varchar(55) NOT NULL,
  `clientId` int(8) NOT NULL,
  `dealName` varchar(255) NOT NULL,
  `dealtId` int(8) NOT NULL,
  `hour` TIMESTAMP(6) NOT NULL,
  `accepted` int(8) NOT NULL,
  `refused` int(8) NOT NULL
);

-- Indexes for dumped tables
-- Indexes for table `users`

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT for dumped tables
-- AUTO_INCREMENT for table `users`

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;