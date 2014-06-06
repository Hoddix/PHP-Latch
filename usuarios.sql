

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_hash` varchar(255) NOT NULL,
  `latch_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;



INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `usuario`, `password`, `user_hash`, `latch_id`) VALUES
(10, 'Javier', 'hoddix@gmail.com', 'hoddix', 'ef6626d28c7fd3ae0f7fbf4dbcf9cfdf7f73187015cff3297ed9312b670789cd', 'vXE1EihzAPk24mNNKkAmoehX8TQnJZ1eWFfBXxaxnvzqRneBHOY73g500UxSTy8Qd', NULL);

