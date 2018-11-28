
-- CREATE EXTENSION unaccent;

DROP VIEW vw_detalhes_servico;

CREATE OR REPLACE VIEW vw_detalhes_servico AS 
 SELECT servico.id_usuario AS id_profisional,
    categoria_servico.id_categoria_servico,
    categoria_servico.nome_categoria_servico,
    categoria_servico.icon_name,
    servico.id_usuario,
    servico.id_servico,
    servico.titulo_servico,
    servico.descricao_servico,
    servico.data_servico,
    servico.numero_visualizacoes,
    servico.dias_semana,
    servico.hora_inicial,
    servico.hora_final,
    servico.inativo,
    usuario.nome_usuario,
    usuario.email_usuario,
    usuario_perfil.pessoa_fisica_juridica,
    usuario_perfil.cpf,
    usuario_perfil.cnpj,
    usuario_perfil.inscricao_estadual,
    usuario_perfil.telefone1,
    usuario_perfil.telefone2,
    usuario_perfil.cep,
    usuario_perfil.id_cidade,
    usuario_perfil.profissao,
    usuario_perfil.funcao,
    usuario_perfil.avaliacao_positiva,
    usuario_perfil.avaliacao_negativa,
    usuario_perfil.foto_perfil,
    ( SELECT (cidade.cidade::text || ' - '::text) || cidade.uf::text
           FROM cidade
          WHERE cidade.id_cidade = usuario_perfil.id_cidade) AS cidade,
    usuario.data_cadastro
   FROM categoria_servico
     JOIN servico USING (id_categoria_servico)
     JOIN usuario USING (id_usuario)
     JOIN usuario_perfil USING (id_usuario);


DROP VIEW vw_listar_servicos_categoria;

CREATE OR REPLACE VIEW vw_listar_servicos_categoria AS 
 SELECT categoria_servico.id_categoria_servico,
    servico.id_servico,
    servico.titulo_servico,
    servico.descricao_servico,
    servico.data_servico,
    servico.numero_visualizacoes,
    servico.inativo,
    usuario.nome_usuario,
    usuario_perfil.pessoa_fisica_juridica,
    usuario_perfil.cpf,
    usuario_perfil.cnpj,
    usuario_perfil.inscricao_estadual,
    usuario.data_cadastro
   FROM categoria_servico
     JOIN servico USING (id_categoria_servico)
     JOIN usuario USING (id_usuario)
     JOIN usuario_perfil USING (id_usuario);
     
ALTER TABLE vw_listar_servicos_categoria
  OWNER TO postgres;

ALTER TABLE usuario ADD COLUMN verificado BOOLEAN DEFAULT FALSE NOT NULL;