@extends('main')
@section('title', 'Welcome')
	@section('content') 
	<div class="content">
		<div class="ct-list" id="notice-con"> 
			<div class="item" style="display: block;">  
				<div class="banner">
					<div class="item">
						<a href="#" title="">
							<img src="{{asset('custom/images/banner/banner1.jpg')}}" alt="activated carbon for civilian water, Industrial water & enviromental water">
						</a> 
					</div>
					<div class="item">
						<a href="#" title="">
							<img src="{{asset('custom/images/banner/banner2.jpg')}}" alt="Activated Carbon">
						</a> 
					</div>
				</div>
 
				<div class="bc-color ind_box1">
					<div class="commodity wrapper">
						<div class="shop_top tc">
							<div class="shop_hot">
								<h1>
									<a href="products/index.html">
										<span>PRODUK</span>
										KAMI
									</a>

									<div class="btn btn-primary bg-blue float-right">Lihat Semua</div>
								</h1>
							</div>
							<div class="border_b">
							</div>
						</div>
						
						
						<div class="commodity-tit" >
							<ul class="layui_tab_title">
								<li class="commodity-li hover">Activated Carbon<div class="daosanjiao1 iconfont iconicon-up1"></div></li>
								<li class="commodity-li">Filter Media & Chemical<div class="daosanjiao2 iconfont iconicon-up1"></div></li>
								<li class="commodity-li">Carbon Additve<div class="daosanjiao3 iconfont iconicon-up1"></div></li>
							</ul>
						</div>
						<div class="commodity-con" id="tab_con">
							<div class="commodity-con_item">
								<div class="commodity-item include_con">
									<ul class="tab_content ind_solu_scroll">

										<li>
											<a href="products/activated-carbon/granular-activated-carbon.html" title="Granular Activated Carbon">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/activated-carbon/granule%20ac/granule%20activated%20carbon.jpg')}}" alt="Granular Activated Carbon" >
											    </div>
											    <p>Granular Activated Carbon</p>
                                            </a>
										</li><li>
											<a href="products/activated-carbon/columnar-activated-carbon.html" title="Pellets Activated Carbon">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/carbon-additve/columnar.jpg')}}" alt="Pellets Activated Carbon" >
											    </div>
											    <p>Pellets Activated Carbon</p>
                                            </a>
										</li><li>
											<a href="products/activated-carbon/powdered-activated-carbon.html" title="Powdered Activated Carbon">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/carbon-additve/coal-base.jpg')}}" alt="Powdered activated carbon" >
											    </div>
											    <p>Powdered Activated Carbon</p>
                                            </a>
										</li><li>
											<a href="products/activated-carbon/coal-base-activated-carbon.html" title="Coal Based Activated Carbon">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/carbon-additve/columnar1.jpg')}}" alt="Coal Base Activated Carbon" >
											    </div>
											    <p>Coal Based Activated Carbon</p>
                                            </a>
										</li><li>
											<a href="products/activated-carbon/coconut-shell-base-activated-carbon.html" title="Coconut Shell Activated Carbon">
                                                <div class="card">
												    <img src="{{asset('custom/images/Coconut_Shell_Activated_Carbon_1.jpg')}}" alt="Coconut Shell Activated Carbon" >
											    </div>
											    <p>Coconut Shell Activated Carbon</p>
                                            </a>
										</li><li>
											<a href="products/activated-carbon/wood-base-activated-carbon.html" title="Wood Activated Carbon">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/carbon-additve/wood-base-activated-carbon.jpg')}}" alt="Wood-base activated carbon" >
											    </div>
											    <p>Wood Activated Carbon</p>
                                            </a>
										</li><li>
											<a href="products/activated-carbon/honeycomb-activated-carbon.html" title="Honeycomb Activated Carbon">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/carbon-additve/honeycomb-activated-carbon.jpg')}}" alt="Honeycomb activated carbon" >
											    </div>
											    <p>Honeycomb Activated Carbon</p>
                                            </a>
										</li><li>
											<a href="products/activated-carbon/desulfurization-and-denitrification.html" title="Desulfurization and Denitrification">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/carbon-additve/desulfurization-and-denitrification.jpg')}}" alt="Desulfurization and Denitrification " >
											    </div>
											    <p>Desulfurization and Denitrification</p>
                                            </a>
										</li>
										
									</ul><ul class="tab_content ind_solu_scroll">

										<li>
											<a href="products/chemical/polyaluminum-chloride.html" title="POLYALUMINUM CHLORIDE (PAC)">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/pharmacy/polyaluminum-chloride.jpg')}}" alt="POLYALUMINUM CHLORIDE" >
											    </div>
											    <p>POLYALUMINUM CHLORIDE (PAC)</p>
                                            </a>
										</li><li>
											<a href="products/chemical/18.html" title="Cationic Polyacrylamide">
                                                <div class="card">
												    <img src="{{asset('custom/files/PAM/CPAM_1.jpg')}}" alt="Cationic Polyacrylamide" >
											    </div>
											    <p>Cationic Polyacrylamide</p>
                                            </a>
										</li><li>
											<a href="products/chemical/anionic-polyacrylamide.html" title="Anionic polyacrylamide ">
                                                <div class="card">
												    <img src="{{asset('custom/files/PAM/APAM%20_1.jpg')}}" alt="Anionic Polyacrylamide" >
											    </div>
											    <p>Anionic polyacrylamide </p>
                                            </a>
										</li><li>
											<a href="products/chemical/polyacrylamide.html" title="Polyacrylamide">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/pharmacy/polyacrylamide.jpg')}}" alt="Polyacrylamide" >
											    </div>
											    <p>Polyacrylamide</p>
                                            </a>
										</li>
										
									</ul><ul class="tab_content ind_solu_scroll">

										<li>
											<a href="products/carbon-additve/instant-graphite-carbon-raiser.html" title="Instant Graphite Carbon Raiser">
                                                <div class="card">
												    <img src="{{asset('custom/files/carbon%20raiser/instant%20graphite%20cylinder%20carbon%20raiser/1%20(1).jpg')}}" alt="" >
											    </div>
											    <p>Instant Graphite Carbon Raiser</p>
                                            </a>
										</li><li>
											<a href="products/carbon-additve/graphite-carburizer.html" title="Graphite Carburizer">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/activated-carbon/graphite-carburizer00.jpg')}}" alt="Graphite Carburizer" >
											    </div>
											    <p>Graphite Carburizer</p>
                                            </a>
										</li><li>
											<a href="products/carbon-additve/petroleum-coke-carburizer.html" title="Petroleum Coke Carburizer">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/activated-carbon/petroleum-coke-carburizer00.jpg')}}" alt="Petroleum Coke Carburizer" >
											    </div>
											    <p>Petroleum Coke Carburizer</p>
                                            </a>
										</li><li>
											<a href="products/carbon-additve/coal-carburizer.html" title="Coal Carburizer">
                                                <div class="card">
												    <img class="rounded" src="{{asset('custom/images/products/activated-carbon/coal-carburizer00.jpg')}}" alt="Coal Carburizer" >
											    </div>
											    <p>Coal Carburizer</p>
                                            </a>
										</li>
										
									</ul>								</div>
								<div class="commodity-con-scroll_btn commodity-con_item_prev">
									<p class="iconfont iconduoyuyan"></p>
								</div>
								<div class="commodity-con-scroll_btn commodity-con_item_next">
									<p class="iconfont iconduoyuyan"></p>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				
				<div class="bc-color ind_box1">
					<div class="commodity wrapper">
						<div class="shop_top tc">
							<div class="shop_hot">
								<h1>
									<a href="products/index.html">
										<span>APLIKASI</span>
										KAMI
									</a>

									<div class="btn btn-primary bg-blue float-right">Lihat Semua</div>
								</h1>
							</div>
							<div class="border_b">
						</div>
					</div>
					
				<!--tab/ application-->
				<div class="application_tab">
				  <ul class="layui_tab_title">
					<li>Application in Liquid Phase<div class="daosanjiao1 iconfont iconicon-up1"></div></li>
					<li>Application in Gas Phase<div class="daosanjiao2 iconfont iconicon-up1"></div></li>
					<li>Other Special Application<div class="daosanjiao3 iconfont iconicon-up1"></div></li>
				  </ul>
				<div class="layui-tab-content">
				    <div class="layui-tab-item layui-tab-item1 layui-show ind_application">

						<div class="layui-tab-item-ul wrapper">
							<dl class="layui-tab-item-li">
								<dd>Application in Liquid Phase</dd>
								<dd>
									Beverage ,wine ,food ,medical treatment ,chemical industry .Activated carbon has various functions in industrial prodcution and it is widely used for decolorization ,deodorization ...
								</dd>
								<dd>
									<a href="application/application-in-liquid-phase/index.html" title="Application in Liquid Phase">Learn about app details</a>
								</dd>
							</dl>
						</div><div class="layui-tab-item-ul wrapper">
							<dl class="layui-tab-item-li">
								<dd>Application in Gas Phase</dd>
								<dd>
									Activated carbons are mainly used in the refinement of carbonate gas for beverage ,in the industrial preparation of helium gas for natural gas ,in ozone decomposition ,in separation of carbon dioxide in flue gas etc.
								</dd>
								<dd>
									<a href="application/application-in-gas-phase/index.html" title="Application in Gas Phase">Learn about app details</a>
								</dd>
							</dl>
						</div><div class="layui-tab-item-ul wrapper">
							<dl class="layui-tab-item-li">
								<dd>Other Special Application</dd>
								<dd>
									Ningxia Yongruida activated carbon is used for decoloring of cane sugar ,glucose ,edibleoil、amino acide manufacture 、monosodium glutamate、medicine,etc .to make it clearer ,and also for removing heavy metal in food ,for example ,Plumbum,mercury ,and arsenic in wine ,chocolate and candy .
								</dd>
								<dd>
									<a href="application/other-special-application/index.html" title="Other Special Application">Learn about app details</a>
								</dd>
							</dl>
						</div>						
					</div>
                </div> 
				</div>
                
				<div class="tab-shop wrapper" id="tab-shop">
					<div class="shop_top tc">
						<div class="shop_hot">
						<h1>
									<a href="products/index.html">
										<span>SOLUSI</span>
										KAMI
									</a>

									<div class="btn btn-primary bg-blue float-right">Lihat Semua</div>
								</h1>
						</div>
						<div class="border_b">
							
						</div>
					</div>
					
					<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
					  <ul class="layui-tab-title ind_solution">
					  						    <li>
							<a href="javascript:;">
								<div class="iconfont iconkongqijinghuaqi">
								
								</div>
								<p>
									Air Purification								</p>
							</a>
							
						</li>
					    					    <li>
							<a href="javascript:;">
								<div class="iconfont iconshuichulixitong">
								
								</div>
								<p>
									Water treatment								</p>
							</a>
							
						</li>
					    					    <li>
							<a href="javascript:;">
								<div class="iconfont icontianjiayaopin-01">
								
								</div>
								<p>
									Pharmacy								</p>
							</a>
							
						</li>
					    					    <li>
							<a href="javascript:;">
								<div class="iconfont iconcanyin5">
								
								</div>
								<p>
									Food & Beverage								</p>
							</a>
							
						</li>
					    					    <li>
							<a href="javascript:;">
								<div class="iconfont iconyoujirongjixinxi">
								
								</div>
								<p>
									Solvent Recovery								</p>
							</a>
							
						</li>
					    					    <li>
							<a href="javascript:;">
								<div class="iconfont iconeryanghualiu">
								
								</div>
								<p>
									Desulfurization								</p>
							</a>
							
						</li>
					    					    <li>
							<a href="javascript:;">
								<div class="iconfont iconhuaxue">
								
								</div>
								<p>
									Chemistry								</p>
							</a>
							
						</li>
					    					    <li>
							<a href="javascript:;">
								<div class="iconfont iconjinshuyuancaijiance">
								
								</div>
								<p>
									Precious Metal Recovery								</p>
							</a>
							
						</li>
					    			
					  </ul>
					  <div class="layui-tab-content clearfix" id="layui-tab-content-id">
					  						    <div class="layui-tab-item layui-show">
							<div class="tab-shop-img">
                                <a href="solution/air-purification.html" title="Air Purification">
                                    <img src="{{asset('custom/images/solutionfangan-1.jpg')}}" alt="">
                                </a>
                            </div>
                            <div class="introduce">
                                <a href="solution/air-purification.html" title="Air Purification">
                                    <div class="introduce-title">
                                        Air Purification                                    </div>
                                    <div class="introduce-content">
                                        Today, all over the world are facing with the problem of how to solve air pollution. As society continues to developing, pollution is difficult to reach from the root cause, and only can use effective products to control air pollution. For many years, the most widely used air pollution treatment product in the world is activated carbon (AC) . Activated carbon has become the default choice for pollution filtration in various industries. Ningxia Yongruida Activated Carbon factory produce particular AC used in air purification.                                    </div>
                                </a>
                                <div class="introduce-more">
                                    <a href="solution/air-purification.html" title="Air Purification">View More</a>
                                </div>
                            </div>
						</div>
					    					    					    <div class="layui-tab-item">
							<div class="tab-shop-img">
                                <a href="solution/water-treatment.html" title="Water treatment">
                                    <img src="{{asset('custom/images/Solution-list02_03.jpg')}}" alt="">
                                </a>
                            </div>
                            <div class="introduce">
                                <a href="solution/water-treatment.html" title="Water treatment">
                                    <div class="introduce-title">
                                        Water treatment                                    </div>
                                    <div class="introduce-content">
                                        The world is facing an invisible water quality crisis, causing one-third of potential economic growth in heavily polluted areas, posing a threat to human and environmental health.
World Bank research in the report shows that a combination of microorganisms, sewage, chemicals, and plastics draws oxygen from water supplies and turns them into poisons for humans and ecosystems.
Activated Carbon (AC) is the most basic material used for water treatment,  so activated carbon will play a vital role in improving human water pollution.                                    </div>
                                </a>
                                <div class="introduce-more">
                                    <a href="solution/water-treatment.html" title="Water treatment">View More</a>
                                </div>
                            </div>
						</div>
					    					    <div class="layui-tab-item">
							<div class="tab-shop-img">
                                <a href="solution/pharmacy.html" title="Pharmacy">
                                    <img src="{{asset('custom/images/Solution-list03_03.jpg')}}" alt="Pharmacy">
                                </a>
                            </div>
                            <div class="introduce">
                                <a href="solution/pharmacy.html" title="Pharmacy">
                                    <div class="introduce-title">
                                        Pharmacy                                    </div>
                                    <div class="introduce-content">
                                        Activated charcoal was considered the universal antidote. Nowadays, it is promoted as a potent natural treatment.
It has a variety of proposed benefits, ranging from lowering cholesterol to whitening teeth and curing hangovers. Sometimes used to manage a poisoning or overdose. It helps rid the body of unpleasant substances.
                                    </div>
                                </a>
                                <div class="introduce-more">
                                    <a href="solution/pharmacy.html" title="Pharmacy">View More</a>
                                </div>
                            </div>
						</div>
					    					    <div class="layui-tab-item">
							<div class="tab-shop-img">
                                <a href="solution/food-and-beverage.html" title="Food & Beverage">
                                    <img src="{{asset('custom/images/Solution-list04_03.jpg')}}" alt="Food & Beverage">
                                </a>
                            </div>
                            <div class="introduce">
                                <a href="solution/food-and-beverage.html" title="Food & Beverage">
                                    <div class="introduce-title">
                                        Food & Beverage                                    </div>
                                    <div class="introduce-content">
                                        Activated Charcoal is used in food to colour it black and for its supposed health benefits.
Sometimes it is used as a food ingredient. This is typically made from bamboo or coconut shell. It gives food an earthy, smoky taste and the black colouring gives the food an exotic, fashionable appearance.
Health benefits have been claimed for charcoal back to classical times, when Hippocrates and Pliny recommended it for conditions such as anthrax and vertigo. Activated charcoal adsorbs chemicals and so may bind to both toxins and vital nutrients such as vitamins. Its effects are therefore broad and indiscriminate.                                    </div>
                                </a>
                                <div class="introduce-more">
                                    <a href="solution/food-and-beverage.html" title="Food & Beverage">View More</a>
                                </div>
                            </div>
						</div>
					    					    <div class="layui-tab-item">
							<div class="tab-shop-img">
                                <a href="solution/solvent-recovery.html" title="Solvent Recovery">
                                    <img src="{{asset('custom/images/Solution-list05_03.jpg')}}" alt="Solvent Recovery">
                                </a>
                            </div>
                            <div class="introduce">
                                <a href="solution/solvent-recovery.html" title="Solvent Recovery">
                                    <div class="introduce-title">
                                        Solvent Recovery                                    </div>
                                    <div class="introduce-content">
                                        The solvent processing industry, e. g. the printing industry, dry cleaning shops and paint shops, depend on solvents which are vaporized in the course of the production process. Recovery of these solvents from the process exhaust air is desirable both from economic and ecological aspects. 
The solvent recovery process relies on high-quality activated carbon grades with a well-balanced pore structure. Depending on the type of solvent to be removed, activated carbon grades of different raw material origins or low-ash carbon grades are employed to give the best treatment result.                                    </div>
                                </a>
                                <div class="introduce-more">
                                    <a href="solution/solvent-recovery.html" title="Solvent Recovery">View More</a>
                                </div>
                            </div>
						</div>
					    					    <div class="layui-tab-item">
							<div class="tab-shop-img">
                                <a href="solution/6.html" title="Desulfurization">
                                    <img src="{{asset('custom/images/Solution-list06_03.jpg')}}" alt="">
                                </a>
                            </div>
                            <div class="introduce">
                                <a href="solution/6.html" title="Desulfurization">
                                    <div class="introduce-title">
                                        Desulfurization                                    </div>
                                    <div class="introduce-content">
                                        Application of activated carbon for air cleaning from sulfur containing species, such as hydrogen sulfide, sulfur dioxide, and mercaptans. The removal of organic sulfur-containing compounds from both gaseous and liquid fuel is addressed. The emphasis is placed on the role of activated carbon surfaces, either unmodified or modified in the processes of adsorption and catalytic oxidation of the pollutants.                                    </div>
                                </a>
                                <div class="introduce-more">
                                    <a href="solution/6.html" title="Desulfurization">View More</a>
                                </div>
                            </div>
						</div>
					    					    <div class="layui-tab-item">
							<div class="tab-shop-img">
                                <a href="solution/chemistry.html" title="Chemistry">
                                    <img src="{{asset('custom/images/Solution-list07_03.jpg')}}" alt="Chemistry">
                                </a>
                            </div>
                            <div class="introduce">
                                <a href="solution/chemistry.html" title="Chemistry">
                                    <div class="introduce-title">
                                        Chemistry                                    </div>
                                    <div class="introduce-content">
                                        Activated carbons, because of their unique surface chemistry, act not only as adsorbents but also as catalysts for the oxidation of inorganic and organic species, and their surface can be modified and tailored toward desired applications.
										Gas Disposal&mdash;Removal of Mercury:
										Gas Disposal&mdash;Gas Purifying Liquid
										Industrial Water--- Purification:
										Control and Prevention of Pollution--- Purifica                                    
									</div>
                                </a>
                                <div class="introduce-more">
                                    <a href="solution/chemistry.html" title="Chemistry">View More</a>
                                </div>
                            </div>
						</div>
					    					    <div class="layui-tab-item">
							<div class="tab-shop-img">
                                <a href="solution/precious-metal-recovery.html" title="Precious Metal Recovery">
                                    <img src="{{asset('custom/images/Solution-list08_03.jpg')}}" alt="Precious Metal Recovery">
                                </a>
                            </div>
                            <div class="introduce">
                                <a href="solution/precious-metal-recovery.html" title="Precious Metal Recovery">
                                    <div class="introduce-title">
                                        Precious Metal Recovery                                    </div>
                                    <div class="introduce-content">
                                        A major use of activated carbon in mining is in gold recovery, where granular activated carbon (GAC) is used for adsorption of the gold-cyanide complex in carbon-in-pulp (CIP) and carbon-in-leach (CIL) systems, or in carbon-in-column (CIC) systems after a heap leach operation carbon. We produce a wide and dedicated product range including extruded and high-quality broken grades for gold recovery applications. These activated carbons combine superior hardness with adsorption kinetics and capacity, resulting in fewer fines and associated gold losses.                                    </div>
                                </a>
                                <div class="introduce-more">
                                    <a href="solution/precious-metal-recovery.html" title="Precious Metal Recovery">View More</a>
                                </div>
                            </div>
						</div>
					    					  </div>
					</div>
                </div>
                
				<div class="about-us"> 
					<div class="about-us-layout wrapper">
						<div class="about-us-our">
							<div class="about-us-title">
								About us  /  Company Profile
							</div>
							<div class="about-us-title-one">
								<dd>LET EVERY DROP OF WATER</dd>
								<d>
									AND AIR TURN BACK TO 
								</d><br>
								THE NATURE
							</div>
							<div class="about-us-introduce">
								Ningxia Yongruida Carbon Co,.Ltd  was founded in 2003.With an area of over 50000 
								square meters ,our factory is located in the city of Shizuishan Ningxia .Our factory 
								has two kioton level Slep Activated Fumace and two Carbonized convertors .
							</div>
							<div class="about-us-more">
								<a href="about/index.html">View More</a>
							</div>
						</div>
					</div>
				</div>
				 
				<div class="news">
					<div class="news-layout wrapper">
						<div class="news-left">
							<div class="news-left-one">
								<a href="news/index.html">Media Information</a>
							</div>
							<div class="news-left-two">
								<a href="news/index.html">NEWS <dd>CENTER</dd></a>
							</div>
							<div class="news-left-three">
								
							</div>
						</div>
						<div class="news-more">
							<a href="news/index.html">View More</a>
						</div>
					</div>
				</div> 
				
				<div class="issue newsdisn">
					<div class="issue-layout wrapper banner">
												<div class="issue_images_con">
							<a href="news/coal-activated-carbon-pellets-wastewater-treatment.html" title="Coal Activated Carbon Pellets Are Playing an Important Role in Wastewater Treatment">
                                <div class="issue_images">
                                    <img src="{{asset('custom/files/activated-carbon-pellets_2.jpg')}}" alt="">
                                </div>
                                <div class="issue-news">
                                    <p>2019-12-26</p>
                                    <a href="news/coal-activated-carbon-pellets-wastewater-treatment.html" title="Coal Activated Carbon Pellets Are Playing an Important Role in Wastewater Treatment">Coal Activated Carbon Pellets Are Playing an Important Role in Wastewater Treatment</a>
                                </div>
                            </a>
						</div>
												<div class="issue_images_con">
							<a href="news/the-nature-of-activated-carbon.html" title="Understanding Of The Nature Of Activated Carbon">
                                <div class="issue_images">
                                    <img src="{{asset('custom/images/news_4.jpg')}}" alt="The Nature Of Activated Carbon">
                                </div>
                                <div class="issue-news">
                                    <p>2019-11-08</p>
                                    <a href="news/the-nature-of-activated-carbon.html" title="Understanding Of The Nature Of Activated Carbon">Understanding Of The Nature Of Activated Carbon</a>
                                </div>
                            </a>
						</div>
												<div class="issue_images_con">
							<a href="news/the-abcs-activated-carbon-.html" title="The ABC&#039;S activated carbon ">
                                <div class="issue_images">
                                    <img src="{{asset('custom/images/news/news.jpg')}}" alt="The ABC'S activated carbon ">
                                </div>
                                <div class="issue-news">
                                    <p>2019-11-08</p>
                                    <a href="news/the-abcs-activated-carbon-.html" title="The ABC&#039;S activated carbon ">The ABC&#039;S activated carbon </a>
                                </div>
                            </a>
						</div>
											</div>
				</div>

				<div class="issue newsdisb">
					<div class="issue-layout wrapper">
												<div class="issue_images_con">
							<a href="news/coal-activated-carbon-pellets-wastewater-treatment.html" title="Coal Activated Carbon Pellets Are Playing an Important Role in Wastewater Treatment">
                                <div class="issue_images">
                                    <img src="{{asset('custom/files/activated-carbon-pellets_2.jpg')}}" alt="">
                                </div>
                                <div class="issue-news">
                                    <p>2019-12-26</p>
                                    <a href="news/coal-activated-carbon-pellets-wastewater-treatment.html" title="Coal Activated Carbon Pellets Are Playing an Important Role in Wastewater Treatment">Coal Activated Carbon Pellets Are Playing an Important Role in Wastewater Treatment</a>
                                
                                </div>
                            </a>
						</div>
												<div class="issue_images_con">
							<a href="news/the-nature-of-activated-carbon.html" title="Understanding Of The Nature Of Activated Carbon">
                                <div class="issue_images">
                                    <img src="{{asset('custom/images/news_4.jpg')}}" alt="The Nature Of Activated Carbon">
                                </div>
                                <div class="issue-news">
                                    <p>2019-11-08</p>
                                    <a href="news/the-nature-of-activated-carbon.html" title="Understanding Of The Nature Of Activated Carbon">Understanding Of The Nature Of Activated Carbon</a>
                                
                                </div>
                            </a>
						</div>
												<div class="issue_images_con">
							<a href="news/the-abcs-activated-carbon-.html" title="The ABC&#039;S activated carbon ">
                                <div class="issue_images">
                                    <img src="{{asset('custom/images/news/news.jpg')}}" alt="The ABC'S activated carbon ">
                                </div>
                                <div class="issue-news">
                                    <p>2019-11-08</p>
                                    <a href="news/the-abcs-activated-carbon-.html" title="The ABC&#039;S activated carbon ">The ABC&#039;S activated carbon </a>
                                
                                </div>
                            </a>
						</div>
												
					</div>
				</div>
			
				
				
			</div>
		</div>
		</div>
@endsection