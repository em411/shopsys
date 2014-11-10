<?php

namespace SS6\ShopBundle\Form\Admin\Customer;

use SS6\ShopBundle\Model\Customer\UserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;

class UserFormType extends AbstractType {

	/**
	 * @var string
	 */
	private $scenario;

	/**
	 * @var \SS6\ShopBundle\Model\Domain\Config\DomainConfig[]
	 */
	private $domains;

	/**
	 * @var \SS6\ShopBundle\Model\Domain\SelectedDomain
	 */
	private $selectedDomain;

	/**
	 * @var \SS6\ShopBundle\Model\Pricing\Group\PricingGroup[]
	 */
	private $pricingGroups;

	/**
	 * @param string $scenario
	 * @param \SS6\ShopBundle\Model\Domain\Config\DomainConfig[] $domains
	 * @param \SS6\ShopBundle\Model\Domain\SelectedDomain $selectedDomain
	 * @param \SS6\ShopBundle\Model\Pricing\Group\PricingGroup[]|null $pricingGroups
	 */
	public function __construct($scenario, $domains = null, $selectedDomain = null, $pricingGroups = null) {
		$this->scenario = $scenario;
		$this->domains = $domains;
		$this->selectedDomain = $selectedDomain;
		$this->pricingGroups = $pricingGroups;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'user';
	}

	/**
	 * @param \Symfony\Component\Form\FormBuilderInterface $builder
	 * @param array $options
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('firstName', 'text', array(
				'constraints' => array(
					new Constraints\NotBlank(array('message' => 'Vyplňte prosím jméno')),
				),
			))
			->add('lastName', 'text', array(
				'constraints' => array(
					new Constraints\NotBlank(array('message' => 'Vyplňte prosím příjmení')),
				),
			))
			->add('email', 'email', array(
				'constraints' => array(
					new Constraints\NotBlank(array('message' => 'Vyplňte prosím e-mail')),
					new Constraints\Email(array('message' => 'Vyplňte prosím platný e-mail')),
				)
			))
			->add('password', 'repeated', array(
				'type' => 'password',
				'required' => $this->scenario === CustomerFormType::SCENARIO_CREATE,
				'first_options' => array(
					'constraints' => array(
						new Constraints\NotBlank(array(
							'message' => 'Vyplňte prosím heslo',
							'groups' => array('create'),
						)),
						new Constraints\Length(array('min' => 5, 'minMessage' => 'Heslo musí mít minimálně {{ limit }} znaků')),
					)
				),
				'invalid_message' => 'Hesla se neshodují',
			));

		if ($this->scenario === CustomerFormType::SCENARIO_CREATE) {
			$domainsNamesById = array();
			foreach ($this->domains as $domain) {
				$domainsNamesById[$domain->getId()] = $domain->getDomain();
			}

			$builder
				->add('domainId', 'choice', array(
					'required' => true,
					'choices' => $domainsNamesById,
					'data' => $this->selectedDomain->getId(),
				));
		}
		
		$builder
			->add('pricingGroup', 'choice', array(
			'required' => false,
			'choice_list' => new ObjectChoiceList($this->pricingGroups, 'name', array(), null, 'id'),
		));
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		$resolver->setDefaults(array(
			'data_class' => UserData::class,
			'attr' => array('novalidate' => 'novalidate'),
		));
	}

}
