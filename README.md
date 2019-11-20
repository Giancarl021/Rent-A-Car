# Rent-A-Car

## Estado: *Caçando Bugs*

Trabalho de PHP 2º Semestre, no qual o desafio é:

 **Escolher um tema e desenvolver o sistema na linguagem PHP. A páginas HTML devem possuir CSS.**

 


 **Locadora de veículos**

 Locadora de Veículos Uma locadora aluga carros aos clientes previamente cadastrados. Caso um carro, disponível, seja escolhido pelo cliente este é alugado, sendo registrada a data inicial junto ao aluguel. Para que o cliente possa alugar um carro, este não pode estar com dívida pendente. Os carros são descritos pela placa, ano, modelo, descrição, km, preço por km, situação (disponível, etc), taxa diária, observações (informações gerais). Os clientes são cadastrados pelo seu cpf, nome, endereço, telefone e dívida (reservado para registrar pagamentos pendentes). Quando o cliente devolve o carro, a situação do carro é alterada para “disponível”, o km é atualizado com o km atual do carro e um recibo é emitido, baseado nos km's rodados e nos dias em que ficou com o carro. Ainda na atividade de devolução é removido o registro do aluguel e, caso o cliente não possa pagar, a dívida do aluguel é registrada junto ao cliente. O cliente pode a qualquer momento pagar sua dívida e o gerente pode solicitar relatórios sobre o valor da dívida dos clientes.

 O sistema deve possuir os seguintes menus:

- **Cadastros**

    - Carros
    
    - Clientes
    
    - Aluguel do carro
    
    - Devolução do Carro
    
    - Valor a pagar(dívida)
- **Listagem**

    - Carros Disponíveis
    
    - Carros Alugados
    
    - Clientes
    
    - Clientes com Dívidas
    
    - Total da Dívida dos Clientes
- **Atualização de informações (carro, cliente e aluguel)**
